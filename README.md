fnargApi
========

PHP code for APIs on fnarg.net

For some fun and learning api.fnarg.net is running a Hypermedia service. The 
design pattern that has been implemented is Mike Amundsens [Collection+JSON](http://amundsen.com/media-types/collection/). 
The service is only a partial implementation of the Collection+JSON 
specification. For example it is read-only. Nevertheless, it is freely 
available to anyone interested in developing a Hypermedia client. 

Hypermedia designs are intentionally agnostic about the domain. The test data
was sourced from the website of the [National Oceanic And Atmospheric 
Administration](http://www.ngdc.noaa.gov/hazard/tsu_db.shtml). 
There are ~2.5k tsunami events in the database. Each *collection* contains 
twenty items sorted in reverse chronological order from a given year. 

Summary of the API endpoints
----------------------------

	GET /tsunamis
	GET /tsunamis?year=1961
	GET /tsunamis/123

As you can see it pretty simple :). According to HATEOAS the API index should
contain enough clues to discover the contents within. Here then, is the
starting point for the dialogue.

	>>> Request <<<
	GET /tsunamis
	Host: api.fnarg.net
	Accept: application/vnd.collection+json

	>>> Response <<<
	200 OK HTTP/1.1
	Content-Type: application/vnd.collection+json
	Content-Length: xxx

	{
	    "collection": {
	        "version": "1.0",
	        "href": "http://api.fnarg.net/tsunamis",
	        "queries": [
	            {
	                "href": "http://api.fnarg.net/tsunamis",
	                "rel": "search",
	                "prompt": "Enter year in range -100 to current",
	                "data": [
	                    {
	                        "name": "year",
	                        "value": ""
	                    }
	                ]
	            }
	        ]
	    }
	}
	
Note that the response contains a queries template. The intention is that a client will
use this when building a UI for searching. For an instant example of this try
using a [Collection+JSON client](http://api.fnarg.net/doc/) to call the API. 

Error handling is fairly minimal. The service will complain if the year is not
an integer and the Accept header is (of course) required.
