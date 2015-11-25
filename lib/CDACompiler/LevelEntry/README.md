## 3  ENTRY-LEVEL TEMPLATES ##

This chapter describes the clinical statement entry templates used within the sections of the consolidated documents. Entry templates contain constraints that are required for conformance. 
Entry-level templates are always in sections.
Each entry-level template description contains the following information:
•  Key template metadata (e.g., templateId, etc.)
•  Description and explanatory narrative.
•  Required CDA acts, participants and vocabularies.
•  Optional CDA acts, participants and vocabularies.
Several entry-level templates require an effectiveTime:
The effectiveTime of an observation is the time interval over which the observation is known to be true. The low and high values should be as precise as possible, but no more precise than known. While CDA has multiple mechanisms to record this time interval (e.g., by low and high values, low and width, high and width, or center point and width), we constrain most to use only the low/high form. The low  value is the earliest point for which the condition is known to have existed. The high value, when present, indicates the time at which the observation was no longer known to be true. The full description of effectiveTime and time intervals is contained in the CDA R2 normative edition.
Provenance in entry templates:
In this version of Consolidated CDA, we have added a “SHOULD” Author constraint on several entry-level templates. Authorship and Author timestamps must be explicitly asserted in these cases, unless the values propagated from the document header hold true.
ID in entry templates:
Entry-level templates may also describe an id element, which is an identifier for that entry. This id may be referenced within the document, or by the system receiving the document. The id assigned must be globally unique. 
