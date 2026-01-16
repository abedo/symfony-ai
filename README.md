Register Tools
The following tools can be installed as dedicated packages, no configuration is needed as these bridges come with flex recipes.

composer require symfony/ai-brave-tool
composer require symfony/ai-clock-tool
composer require symfony/ai-firecrawl-tool
composer require symfony/ai-mapbox-tool
composer require symfony/ai-open-meteo-tool
composer require symfony/ai-scraper-tool
composer require symfony/ai-serp-api-tool
composer require symfony/ai-similarity-search-tool
composer require symfony/ai-tavily-tool
composer require symfony/ai-wikipedia-tool
composer require symfony/ai-youtube-tool

Add yaml file, e.g. config/packages/ai_wikipedia_tool.yml


Chat with a specific agent
php bin/console ai:agent:call wikipedia
