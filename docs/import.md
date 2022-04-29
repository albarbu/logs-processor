## Logs import

### Logs import command

The import can be done like this:

* copy the log file to a location under the application's root directory (e.g `data/logs/Aug2021.txt`);
* from the main application container, execute the below Symfony command using the relative path of the log file as parameter:

    `bin/console logs:import data/logs/Aug2021.txt`
* [optional] check the imported entries in the DB container (the table is named "logs").

The columns of the `logs` table are defined like below:<br>
`log_processing_id SERIAL PRIMARY KEY,`  
`file_path VARCHAR(255) NOT NULL,`<br>
`started_at TIMESTAMP NOT NULL,`<br>
`finished_at TIMESTAMP DEFAULT NULL,`<br>
`last_processed_line INT NOT NULL DEFAULT 0,`<br>
`updated_at TIMESTAMP NOT NULL`.


### Resume capability

The import command has "resume" capability, in a way that if something goes wrong at the first run, the number of processed lines is remembered and at a second run it will start processing with the right line.<br><br>
The processing information for a log file import is kept as a record in the database table called `logs_processing` (linked to the file via the `file_path` column).<br>
Also, if a particular log file was imported already, running the import command again will do nothing, just display an appropriate message.
