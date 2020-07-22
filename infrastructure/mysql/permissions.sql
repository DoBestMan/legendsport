revoke all privileges, grant option from 'production'@'%';
revoke all privileges, grant option from 'staging'@'%';
revoke all privileges, grant option from 'qa'@'%';

GRANT ALL on `legendsports-prod`.* to 'production'@'%';
GRANT ALL on `legendsports-staging`.* to 'staging'@'%';
GRANT ALL on `lsqa-%`.* to 'qa'@'%';
