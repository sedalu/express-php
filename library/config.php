<?php
################################################################################
# LIBRARY/CONFIG.PHP                                                           #
################################################################################

# Location of the mySQL server to be used.
$DB[HOST] = 'localhost';
# User to access the mySQL server with.
$DB[USER] = 'root';
# Password for the user.
$DB[PASS] = 'v1r3d3';

# The name of an existing database on the server.
# The user above must have full access to this database.
$DB[DATABASE] = 'test';
# OPTIONAL, a prefix to attach to all tables used my Express
$DB[PREFIX] = 'express_';

# To enable demo mode, set $DEMO = true.
$DEMO = false;
?>