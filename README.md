# PHP ORM
Light orm made to manage your MYSQL databases of your php project.
  
## Configuration
Set the connection parameters to your database in the config file :
```
app/config/config_db.json
```
## Generate the entities 
With the ORM generator you can generate the tables entities of your database. Run this commande line : 
```sh
php generator.php tableName
```
## Request on the database
1. SELECT
2. INSERT
3. UPDATE
4. JOINTURES
5. COUNT