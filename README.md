## Project
This is the product of a practical project of some students of the FHNW School of Business. The prototype will be able to find websites with information about different software products and evaluate the quality of those products with natural language processing.

The development has just started and thus there is no great functionality implemented as described above.

For the implementation we use following tools:
- Laravel: PHP Web-Application framework
- Google programmable search engine
- RapidMiner: Natural Language Processing

## Usage
To use scouti there are several systems involved. For a full instruction, visit the guide (include guide).
Enter the credentials in the `.env` file as follows:
```
GOOGLE_URL=https://customsearch.googleapis.com/customsearch/v1
GOOGLE_API_KEY=personal_api_key
GOOGLE_SEARCH_ENGINE=custom_search_engine_key
```
The entries can vary based on the configuration of the search engine.

## Roadmap
- ~~Set up website and search interface~~ ✅
- ~~Get google search results~~ ✅
- ~~Evaluate search results (what kind of source is it - website or other document)~~ ✅
- ~~Process the data in RapidMiner~~ ✅
- ~~Display the results to the user~~ ✅

## License
The tool is an open-sourced software and licensed under the  [MIT license](https://opensource.org/licenses/MIT).

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
