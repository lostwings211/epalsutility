SET @end_date = '2014-04-01 00:00:00';

select count(*) from users where (date_added <  @end_date) and country in ('ad', 'al', 'am', 'at', 'az', 'ba', 'be', 'bg', 'by', 'ch', 'cy', 'cz', 'de', 'dk', 'ee', 'es', 'fi', 'fo', 'fr', 'ge', 'gr', 'hr', 'hu', 'ie', 'is', 'it', 'lt', 'lu', 'lv', 'mc', 'mt', 'nl', 'no', 'pl', 'pt', 'ro', 'ru', 'se', 'si', 'sk', 'sm', 'tr', 'ua', 'uk', 'va');
