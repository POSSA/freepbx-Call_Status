FeePBX Call Status
===================

Forked from http://sysadminman.net/blog/2013/asterisk-outbound-call-status-page-5600 and modified for FreePBX system running version 2.9 or higher.
This is a simple HTML page that displays all active calls on a FreePBX system. This is not a FreePBX module, and does not require system credentials to view.

Note that I'm weird about dashes in directories and filenames, so I removed the original developer's dashes from call-status making it callstatus.  Small detail, but it might trip you up if you weren't aware of it.

## Requirements
* FreePBX version 2.9 or later
* Tested with Asterisk 1.8 and 11

## Installation
* On a FreePBX system, download the files to the folder `[webroot]/callstatus`
* chown and chmod folder and files as necessary
* In a browser, navigate to `http://<ipaddress>/callstatus`.
* To add to the FreePBX System Status dashboard, modify the `[webroot]/admin/modules/dashboard/page.index.php` file according to the example included here under `httproot...`

As an example, to install on a Centos based distro such as PIAF or the FreePBX distro, you might use the following commands:
```
cd /var/www/html
git clone https://github.com/shdwlynx/freepbx-Call_Status.git callstatus
chown -R asterisk:asterisk /var/www/html/callstatus
```
## License
This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
