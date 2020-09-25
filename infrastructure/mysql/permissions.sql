revoke `cloudsqlsuperuser`@`%` from 'production'@'%';
revoke `cloudsqlsuperuser`@`%` from 'staging'@'%';
revoke `cloudsqlsuperuser`@`%` from 'qa'@'%';

GRANT ALL on `legendsports-prod`.* to 'production'@'%';
GRANT ALL on `legendsports-staging`.* to 'staging'@'%';
GRANT ALL on `lsqa-%`.* to 'qa'@'%';
