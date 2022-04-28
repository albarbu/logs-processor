# Import logs

## Import logs command

The import can be done like this:

* copy the log file to a location under the application's root directory (e.g "data/logs/Aug2021.txt");
* execute the below Symfony command using the relative path of the log file as parameter:

    `bin/console legal1:logs-import data/logs/Aug2021.txt`
* eventually check the imported entries in the DB container (the table is named "logs").

## Resume capability

The import command has "resume" capability, in a way that if something goes wrong at the first run, the number of processed lines is remembered and at a second run it will start processing with the right line.
