function global_variables {
	read -p "Is this a wordpress site? (y/n): " -n 1 -r -e prj_type
    read -p "Enter your project's name: " -e prj_id
    prj_name=$prj_id
	prj_id=${prj_id//-/_}
	prj_id=${prj_id// /}
	prj_id=${prj_id//./}
	prj_id="$(echo $prj_id | tr '[A-Z]' '[a-z]')"
	prj_base_url=${WORK_BASE_URL//%sitename%/$prj_id}
	prj_sub_url=${WORK_SUB_URL//%sitename%/$prj_id}
	prj_full_url=${prj_base_url}${prj_sub_url}
	read -p "Enter your remote repository URL: " -e prj_repo
}

function wp_variables {
	read -p "Enter your project's initials: " -e prj_initials
}

cd $(dirname $0)
clear
source config
global_variables
template_dir=${PWD}
project_dir=${WORK_DIR}${prj_id}

cd $WORK_DIR

if [ ! -d "$prj_id" ]; then
    mkdir $prj_id
fi
cd $prj_id

sed -e "s|%project_name%|${prj_name}|g" -e "s|%project_base_url%|${prj_base_url}|g" -e "s|%project_sub_url%|${prj_sub_url}|g" ${template_dir}/global/samples/config-sample.codekit > config.codekit
cp ${template_dir}/global/samples/.gitignore .

git init

if [[ $prj_type =~ ^[Yy]$ ]]; then

	wp_variables
	wp core download


	wp core config --dbname="${prj_id}" --dbprefix="${prj_initials}_" --dbuser="${LOCAL_DB_USER}" --dbpass="${LOCAL_DB_PASS}" --dbhost="${LOCAL_DB_HOST}"
	sed -i '' -e "s/^define.'DB_.*$//g" -e "s/^\/.*\/$//g" -e "/^$/d" wp-config.php

	sed -e "s|_database_|${prj_id}|" -e "s|_user_|${LOCAL_DB_USER}|" -e "s|_password_|${LOCAL_DB_PASS}|" -e "s|_dbhost_|${LOCAL_DB_HOST}|" -e "s|_home_|${prj_full_url}|" -e "s|_siteurl_|${prj_full_url}|" ${template_dir}/wordpress/samples/config-sample.php > local-config.php

	wp db create
	wp core install --url="${prj_full_url}" --title="${prj_name}" --admin_user="${WP_USER}" --admin_password="${WP_PASS}" --admin_email="${WP_EMAIL}"

	# plugins

	cd $(wp plugin path)
	find . -not -name 'index.php' -not -name '.' -not -name '..' | xargs rm -rf

	cp -a ${template_dir}/wordpress/plugins/. .

	find . -maxdepth 1 -type d -not -name 'index.php' -not -name '.' -not -name '..' | sed -e "s/^\.\///g" | xargs wp plugin activate

	# themes

	cd $(wp theme path)
	find . -not -name 'index.php' -not -name '.' -not -name '..' | xargs rm -rf

	if [ ! -d "${prj_id}-theme" ]; then
	    mkdir ${prj_id}-theme
	fi

	# our theme

	cd ${prj_id}-theme

	sed -e "s|%project_name%|${prj_name}|g" ${template_dir}/wordpress/samples/style-sample.css > style.css

	cp -a ${template_dir}/global/theme/. .

	cp -a ${template_dir}/wordpress/theme/. .

	cp -a ${template_dir}/wordpress/javascript/. javascript/

	# theme folders

	if [ ! -d "layouts" ]; then
	    mkdir layouts
	fi

	if [ ! -d "templates" ]; then
	    mkdir templates
	fi

	if [ ! -d "assets" ]; then
	    mkdir assets
	fi

	if [ ! -d "includes" ]; then
	    mkdir includes
	fi

	# assets

	cd assets

	if [ ! -d "css" ]; then
	    mkdir css
	fi

	if [ ! -d "fonts" ]; then
	    mkdir fonts
	fi

	if [ ! -d "images" ]; then
	    mkdir images
	fi

	if [ ! -d "js" ]; then
	    mkdir js
	fi

	cd fonts

	git clone https://github.com/FortAwesome/Font-Awesome.git fontawesome &> /dev/null

	cd font-awesome

	find . -maxdepth 1 -not -name 'fonts' -not -name 'css' -not -name 'scss' -not -name '.' -not -name '..' | sed -e "s/^\.\///g" | xargs rm -rf

	cd css

	sed -E -e "s/\.fa-/\.dashicons-fa-/g" -e "s/\{/\{font-family:'FontAwesome' \!important;/g" < font-awesome.min.css > font-awesome.dashicons.min.css

	# includes

	cd $(wp theme path)

	cd ${prj_id}-theme

	cd includes

	cp -a ${template_dir}/wordpress/includes/. .

	find . -maxdepth 1 -type f | sed -e "s/^\.\///g" | xargs -I {} mv {} ${prj_initials}-{}
	includes=$(find . -maxdepth 1 -type f | sed -e "s/^\.\///g" | xargs -I {} echo "include('includes/"{}"');")

	# utilities

	cd utilities

	find . -maxdepth 1 -type f | sed -e "s/^\.\///g" | xargs -I {} mv {} ${prj_initials}-{}
	utilities=$(find . -maxdepth 1 -type f | sed -e "s/^\.\///g" | xargs -I {} echo "include('includes/utilities/"{}"');")

	# functions

	cd $(wp theme path)

	cd ${prj_id}-theme

	sed -e "s|%includes%|${includes//$'\n'/$'\\\\\n'}|g" -e "s|%utilities%|${utilities//$'\n'/$'\\\\\n'}|g" ${template_dir}/wordpress/samples/functions-sample.php > functions.php

	# Back to base directory

	cd ${project_dir}

	if [ ! -d "db" ]; then
	    mkdir db
	fi

	wp db export "db/latestdb.sql"

	wp theme activate ${prj_id}-theme

elif [[ $prj_type =~ ^[Nn]$ ]]; then

	cp -a ${template_dir}/global/theme/. .

	cp -a ${template_dir}/static/. .

fi

chmod 644 `find . -type f`

chmod 755 `find . -type d`

git add --all &> /dev/null
git commit -m "Initial Template" &> /dev/null
if [[ $prj_repo ]]; then
	git remote add origin ${prj_repo} &> /dev/null
	git push -u origin master &> /dev/null
fi

osascript <<EOF
tell application "CodeKit"
    add project at path "$project_dir"
end tell
EOF

if [[ $HOSTS ]] && [[ $VHOSTS ]]; then
	echo "127.0.0.1   ${prj_full_url//http:\/\//}" | sudo tee -a $HOSTS
	echo "<VirtualHost *:80>" | sudo tee -a $VHOSTS
	echo "    DocumentRoot \"${project_dir}\"" | sudo tee -a $VHOSTS
	echo "    ServerName ${prj_full_url//http:\/\//}" | sudo tee -a $VHOSTS
	echo "</VirtualHost>" | sudo tee -a $VHOSTS
	sudo apachectl restart
fi