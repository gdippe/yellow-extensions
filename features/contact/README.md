Contact 0.8.8
=============
Email contact page.

<p align="center"><img src="contact-screenshot.png?raw=true" width="795" height="836" alt="Screenshot"></p>

## How to install extension

1. [Download and install Datenstrom Yellow](https://github.com/datenstrom/yellow/).
2. [Download extension](https://github.com/datenstrom/yellow-extensions/raw/master/zip/contact.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy `contact.zip` into your `system/extensions` folder.

To uninstall delete the [extension files](extension.ini).

## How to use a contact page

The contact page is available on your website as `http://website/contact/`. The webmaster receives all contact messages. The webmaster's email is defined in file `system/settings/system.ini`. You can set a different `Author` and `Email` in the [settings](https://github.com/datenstrom/yellow-extensions/tree/master/features/core#settings) at the top of a page. To show a contact form on other pages use a `[contact]` shortcut. You can also add a link to the contact page somewhere on your website.

## How to restrict a contact page

If you don't want that messages are sent to anyone, then restrict emails. Open file `system/settings/system.ini` and change `ContactEmailRestriction: 1`. Users are not allowed to configure an email, all contact messages go directly to the webmaster.

If you don't want that messages with links are sent, then restrict links. Open file `system/settings/system.ini` and change `ContactLinkRestriction: 1`. Emails must not contain clickable links, this blocks many unwanted messages. You can also configure keywords in the spam filter, fortunately, many spammers send the same message multiple times.

## Settings

The following settings can be configured in file `system/settings/system.ini`:

`Author` = name of the webmaster  
`Email` = email of the webmaster  
`ContactLocation` = contact page location  
`ContactEmailRestriction` = enable email restriction, 1 or 0  
`ContactLinkRestriction` = enable link restriction, 1 or 0  
`ContactSpamFilter` = spam filter as regular expression, `none` to disable  

The following settings can be configured in file `system/settings/text.ini`:

`ContactStatusNone` = welcome text on contact page  
`ContactMailFooter` = footer for contact messages  

The following files can be configured:

`system/layouts/contact.html` = layout file for contact page  

## Examples

Adding a contact form:

    [contact]
    [contact /contact/]
    [contact /de/contact/]

Content file with contact form:

    ---
    Title: Example page
    ---
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut 
    labore et dolore magna pizza. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
    nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt 
    in culpa qui officia deserunt mollit anim id est laborum.

    [contact]

Content file with contact link:

    ---
    Title: About
    ---
    For people who make websites. [Contact me](/contact/).
    
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut 
    labore et dolore magna pizza. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
    nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt 
    in culpa qui officia deserunt mollit anim id est laborum.
    
    This website is made with [Datenstrom Yellow](https://datenstrom.se/yellow/).

## Developer

Datenstrom. [Get support](https://datenstrom.se/yellow/help/).

<p>
<a href="README-de.md"><img src="https://raw.githubusercontent.com/datenstrom/yellow-extensions/master/features/help/language-de.png" width="15" height="15" alt="Deutsch">&nbsp; Deutsch</a>&nbsp;
<a href="README.md"><img src="https://raw.githubusercontent.com/datenstrom/yellow-extensions/master/features/help/language-en.png" width="15" height="15" alt="English">&nbsp; English</a>&nbsp;
</p>
