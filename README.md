Symfony 3 Rest Starter
==========

Starter REST Project (Symfony 3 [Login Form, API Auth JWT], Boostrap 3)

### Instructions (Empty Project) ###:
1.- Update dependencies -> composer update
2.- Set the correct values on app/config/parameters.yml.dist and copy/replace as app/config/parameters.yml
3.- Generate pem files for JWT https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation
4.- Check JWT config -> bin/console lexik:jwt:check-config
5.- Generate your Schema -> bin/console doctrine:schema:create
6.- Add Your admin user -> bin/console  UserManage:add-user --is-admin
6.  To Work!