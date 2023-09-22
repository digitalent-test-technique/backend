# Digitalent test-technique `API`

## Configuration de base de données.

1.  Ouvrez le fichier `config/Database.php`
2.  Créer une base de données appelée `digitalent-test-technique-vuejs`
3.  Changez le `$username` et le `$password` aux valeurs valides (vérifiez les utilisateurs de votre base de données).
4.  Créez une table SQL appelée `users` en utilisant cette commande.

```sql
CREATE TABLE users (
  user_id int NOT NULL AUTO_INCREMENT,
  first_name varchar(255),
  last_name varchar(255),
  email varchar(255),
  address varchar(255),
  phone varchar(255),
  password varchar(255),
  primary key (id)
)
```
