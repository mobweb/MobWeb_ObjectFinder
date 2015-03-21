# MobWeb_ObjectFinder extension for Magento

This extension adds an "universal" search form to your Admin Panel. Enter a search string and the extension will search for matching orders, invoices, shipments and credit memos (by their incremental ID) as well as products (by their SKU).

If just one result is found, it will be opened right away, and if multiple results have been found, they will be listed.

Additionally, you may select an object type filter before submitting your search, to limit your search to one object type (for example orders).

This "universal" search makes it possible to very quickly find an object by entering its ID or SKU, for example when using a barcode reader.

The search form is available right in the header of the Admin Panel or on a separate page at the URL */objectfinder*:

![Screenshot](blob/master/screenshot.png)

## Installation

Install using [colinmollenhour/modman](https://github.com/colinmollenhour/modman/).

## Questions? Need help?

Most of my repositories posted here are projects created for customization requests for clients, so they probably aren't very well documented and the code isn't always 100% flexible. If you have a question or are confused about how something is supposed to work, feel free to get in touch and I'll try and help: [info@mobweb.ch](mailto:info@mobweb.ch).