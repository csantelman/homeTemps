# homeTemps
Website to display temperature data

****************************************************************
DESCRIPTION OF FILES IN REPOSITORY
****************************************************************
Screen Shot 2019-02-06 at 3.08.23 PM
- Just a picture of the "24 Hour" page, showing the highchart graph in action.

index.php
- Main starting point of website
- Currently the buttons across the bottom are not hooked up

styles/reset.css
styles/style.css
- Style pages blatently copied from other sources

js/app.js
- Contains java script to update clock displayed on top of page

php/db_config.php
- Contains the connection details for database

current_history.php
current_history_data.php
garage_history.php
garage_history_data.php
inside_history.php
inside_history_data.php
outside_history_data.php
outside_history.php
- Page & data retrieval for highcharts graphs

maps.html
weather.html
- Test programs to work with google maps & open weather api's
- Currently not used, just playing with them

****************************************************************
CONCERNS AND QUESTIONS
****************************************************************
- I'm not too worried about minor css issues, but open to improvements.

- Website security - having the connection details isolated to php/db_config.php is the only way I found to keep them out of all the other php files.  Is this correct?

- This seems to me a very convaluted way to design a website.  All of the child pages (<name>_history.php) and their corresponding data-retireval page (<name>_history_data.php) share most of the same code.  Column names change between them, and some labels, among other things.  What would a better way to accomplish this be?

- Since this is just available in my home network (not exposed to the internet) - if I wanted to have a button on the page insert a row into the database - should ajax be used?  Should this have been ajax based vs. php?
