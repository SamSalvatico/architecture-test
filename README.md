# TourRadar

## Questions

**What problems do you identify in the current setup? Please enumerate them with a brief description of why you believe they are problems, and what risks they carry.**

1. Writing on *TourFiles* storage as JSON file for all importers lacks of possibility to next query data, so we always need to process all the data in the new JSON file;
2. Queue polling could cause a latency between writing / reading;
3. Single TourProcessor instance is not able to process JSON files in parallel;
4. It is not specified, but, basing on the way we are logging data, we could encounter problems about readibility if we have so much importers and, consequently, many log files.