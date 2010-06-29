files = ["config/config.php", "controllers/admin/dashboard.php", "controllers/contact.php", "controllers/dashboard.php", "controllers/home.php", "controllers/login.php", "models/nav_links_model.php", "models/static_pages_model.php", "models/user_model.php", "views/activate_view.php", "views/admin/dashboard_view.php", "views/contact_view.php", "views/dashboard_view.php", "views/forgot_view.php", "views/home_view.php", "views/include/footer.php", "views/include/header.php", "views/include/navigation.php", "views/include/template.php", "views/login_view.php", "views/password_reset_view.php", "views/register_view.php", "config/autoload.php"]

files.sort()
files.reverse()

outfile = ''

while(files) :
	file = files.pop()
	outfile += '>'
	outfile += file
	outfile += '\n'
	f = open('system/application/' + file, 'r')
	inline = f.readline()
	while inline.find('/*') == -1 and inline != '' :
		inline = f.readline()
	
	inline = f.readline()
	while inline.find('*/') == -1 and inline != '' :
		outfile += inline.replace('\r\n', '\n')
		inline = f.readline()
	
	outfile += '\n'
	f.close()

o = open('documentation.txt', 'w')
o.write(outfile)
o.close()