munin_netatmo
=============

Munin-Plugin for netatmo

Configuration
=============

  * Install PHP-CLI
  * Clone the Repository to any Folder you like
  * Add your Netatmo-App-Credentials to ```./Netatmo-API/Config.php```
  * Create Symlinks in your Munin-Plugin-Directory
  * Restart Munin-Node

The Symlinks must follow a specific schema:

```
netatmo_$module_$value
```

Example: 

If your Module is named ```Indoor``` and you want to add the ```Temperature``` to Munin the Symlink-Name has to be: 

```
netatmo_Indoor_Temperature
```

If your Module is named ```Outdoor``` and you want to add the ```Humidity``` the Symlink-Name has to be:

```
netatmo_Outdoor_Humidity
```

The Symlink-Target is always:

``` 
netatmo_.php 
```

Possible Values are:

  * Temperature
  * Humidity
  * Pressure
  * CO2
  * Noise

Please note that Outdoor-Modules only supports ```Temperature``` and ```Humidity```. 

Limitations
===========

Netatmo only allows a very low number of API-Calls. So it might be needed to lower your Munin-Cronjob-Intensity. Otherwise you might get the following error:

```
PHP Fatal error:  Uncaught exception 'NAApiErrorType' with message 'User usage reached' in /srv/git/netatmo/Netatmo-API/NAApiClient.php:356
```

The Default Munin-Setup together with two Netatmo-Modules works fine, though.
