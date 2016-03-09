function global_variables {
	now=$(date +"%Y-%m-%d-%H-%M-%S")
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

source config
global_variables
template_dir=${PWD}
project_dir=${WORK_DIR}${prj_id}

cd $WORK_DIR

if [ ! -d "$prj_id" ]; then
    mkdir $prj_id
fi
cd $prj_id

cp ${template_dir}/global/samples/config-sample.codekit config.codekit
sed -i '' -e "s|%project_name%|${prj_name}|g" -e "s|%project_base_url%|${prj_base_url}|g" -e "s|%project_sub_url%|${prj_sub_url}|g" config.codekit

git init

if [[ $prj_type =~ ^[Yy]$ ]]; then

	wp_variables
	wp core download

	cp ${template_dir}/wordpress/samples/.gitignore-sample .gitignore

	wp core config --dbname="${prj_id}" --dbprefix="${prj_initials}_" --dbuser="${LOCAL_DB_USER}" --dbpass="${LOCAL_DB_PASS}" --dbhost="${LOCAL_DB_HOST}"
	sed -i '' -e "s/^define.'DB_.*$//g" -e "s/^\/.*\/$//g" -e "/^$/d" wp-config.php

	cp  ${template_dir}/wordpress/samples/config-sample.php local-config.php
	sed -i '' -e "s|_database_|${prj_id}|" -e "s|_user_|${LOCAL_DB_USER}|" -e "s|_password_|${LOCAL_DB_PASS}|" -e "s|_dbhost_|${LOCAL_DB_HOST}|" -e "s|_home_|${prj_full_url}|" -e "s|_siteurl_|${prj_full_url}|" local-config.php

	wp db create
	wp core install --url="${prj_full_url}" --title="${prj_name}" --admin_user="${WP_USER}" --admin_password="${WP_PASS}" --admin_email="${WP_EMAIL}"

	# plugins

	cd ${project_dir}/wp-content/plugins/
	find . -not -name 'index.php' -not -name '.' -not -name '..' | xargs rm -rf

	cp -a ${template_dir}/wordpress/plugins/. .

	find . -maxdepth 1 -type d -not -name 'index.php' -not -name '.' -not -name '..' | sed -e "s/^\.\///g" | xargs wp plugin activate

	# themes

	cd ${project_dir}/wp-content/themes/
	find . -not -name 'index.php' -not -name '.' -not -name '..' | xargs rm -rf

	if [ ! -d "${prj_id}-theme" ]; then
	    mkdir ${prj_id}-theme
	fi

	# our theme

	cd ${prj_id}-theme

	cp  ${template_dir}/wordpress/samples/style-sample.css style.css
	sed -i '' -e "s|%project_name%|${prj_name}|g" style.css

	cp -a ${template_dir}/global/theme/. .

	cp -a ${template_dir}/wordpress/theme/. .

	sed -i '' -e "s|%project_initials%|${prj_initials}|g" functions.php

	if [ ! -d "includes" ]; then
	    mkdir includes
	fi

	cp ${template_dir}/wordpress/samples/library-sample.php includes/${prj_initials}-library.php

	if [ ! -d "layouts" ]; then
	    mkdir layouts
	fi

	# Back to base directory

	cd ${project_dir}

	if [ ! -d "db" ]; then
	    mkdir db
	fi

	wp db export "db/latestdb-${now}.sql"
	wp db export "db/latestdb.sql"

	wp theme activate ${prj_id}-theme

elif [[ $prj_type =~ ^[Nn]$ ]]; then

	cp -a ${template_dir}/global/theme/. .

	cp -a ${template_dir}/static/. .

fi

chmod 644 `find . -type f`

chmod 755 `find . -type d`

git add --all
git commit -m "Initial Template"
if [[ $prj_repo ]]; then
	git remote add origin ${prj_repo}
	git push -u origin master
fi

osascript <<EOF
tell application "CodeKit"
    add project at path "$project_dir"
end tell
EOF