# Code review task

### Task definition

* Create REST api endpoint with functionality to send notifications to customer by email or sms, depending on customer settings.
* Customer profile data (settings) is saved in database

### Request:

* url: /api/customer/{code}/notifications (POST)
* json: {"body": "notification text"}