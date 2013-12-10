=== No Weak Passwords ===
Contributors: DavidAnderson
Tags: passwords, weak passwords, ban passwords, ban weak passwords, password strength, security
Requires at least: 3.2
Tested up to: 3.8
Stable tag: 1.0.1
Donate link: http://david.dw-perspective.org.uk/donate
License: MIT

== Description ==

This plugin forbids any user to choose any password from the "common passwords list" obtained from http://www.openwall.com/passwords/wordlists/, and requires any who are already doing so to reset their passwords.

== Upgrade Notice ==
Marked as compatible with 3.1, tweaked install instructions

== Screenshots ==

1. The error message when a user tries to change his password to an easy one
2. The error message when a user with an existing easy password tries to log in

== Installation ==

Standard WordPress plugin installation:

1. Search for "No Weak Passwords" in the WordPress plugin installer
2. Click 'Install'

== Frequently Asked Questions ==

= Where are the configuration settings? =
There are none. If the plugin is active, then it is banning all of its known weak passwords.

= What if one of my users is already using one of those passwords? =
If they try to log in with one of these weak passwords, then they will not succeed, and they will be told to use the 'Lost Password' procedure to obtain a new password.

= What passwords does this plugin ban? =
The 3546 listed in the "common passwords list" as obtained from http://www.openwall.com/passwords/wordlists/ on 16th November 2012.

= I'd like to change the policy; add some different words; forbid too-short passwords, etc. =
Please either send a patch, or make a donation on my donation page, and I will be glad to help. Otherwise, this plugin does all I wanted it to do and I've not got time to develop it further without some compensation.

= I like automating WordPress, and using the command-line. Please tell me more. =
Glad to hear that! You are looking for WordShell, <a href="http://wordshell.net">http://wordshell.net</a>.

== Changelog ==

= 1.0.1 04/19/2013 =
* Marked as compatible with 3.6

= 1.0.1 12/03/2012 =
* Tweaked install instructions
* Marked compatible with 3.1
* Added link to new "use adminstrator password" plugin

= 1.0 11/16/2012 =
* First version

== License ==

Copyright 2012- David Anderson

MIT License:

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
