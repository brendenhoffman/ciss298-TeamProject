# ciss298-TeamProject
The website will automatically build here:
https://ciss298.zenren.xyz

# First Run
- Clone the repository to the web server docs folder.
- Navigate to `localhost/setup/init.php` or int the cli: `php <path to web server>/setup/init.php`

# Snapshots
Weekly snapshots will be made for submission. They will be hosted on the site like:\
`https://ciss298.zenren.xyz/weekX`\
Direct links will be added below.
A new branch will be created to view the files code at the time of the snapshot.

# SQL Connection
In order to maintain portability and flexability, a separate file, db.php is used to establish a connection to the SQL server. No need to write out a connection string, just include the following code in your file:
```
<?php require_once __DIR__ . '/../db.php'; ?>
```
Should a PR be pushed without the correct connection string, verification will fail for the PR and propting you to change the files.

# SQL Layout
To ensure consistency between developement and production, use the following structure in your database:
```
DB NAME: hotel
```

# Dynamic Page Loading
The main page will load the content of other webpages without a full reload of the page. This is a very fast and smooth operation and it also simplifies the code required to write a page. You only need to write the content that would be contained in the `<main>` block. For example if home.html was:
```
<h1>Welcome!</h1>
<p>This is the home page.</p>
```
You would see the formatted contents in the body of the main page (index.php). This is not limited to html, php can be incorporated just by adding a `<?php` block, as well as javascript with a `<script>` block.
