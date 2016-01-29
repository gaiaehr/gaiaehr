## 2  SECTION-LEVEL TEMPLATES ##

This chapter contains the section-level templates referenced by one or more of the document types of this consolidated guide. These templates describe the purpose of each section and the section-level constraints. 
Section-level templates are always included in a document. One and only one of each section type is allowed in a given document instance. Please see the document context tables to determine the sections that are contained in in a given document type. Please see the conformance verb in the conformance statements to determine if it is required (SHALL), strongly recommended (SHOULD) or optional (MAY).
Each section-level template contains the following:
•  Template metadata (e.g., templateId, etc.)
•  Description and explanatory narrative
•  LOINC section code 
•  Section title
•  Requirements for a text element 
•  Entry-level template names and Ids for referenced templates (required and optional)
Narrative Text
The text element within the section stores the narrative to be rendered, as described in the CDA R2 specification, and is referred to as the CDA narrative block.
The content model of the CDA narrative block schema is hand crafted to meet requirements of human readability and rendering. The schema is registered as a MIME type (text/x-hl7-text+xml), which is the fixed media type for the text element.
As noted in the CDA R2 specification, the document originator is responsible for ensuring that the narrative block contains the complete, human readable, attested content of the section. Structured entries support computer processing and computation and are not a replacement for the attestable, human-readable content of the CDA narrative block. The special case of structured entries with an entry relationship of "DRIV" (is derived from) indicates to the receiving application that the source of the narrative block is the structured entries, and that the contents of the two are clinically equivalent.  
As for all CDA documents—even when a report consisting entirely of structured entries is transformed into CDA—the encoding application must ensure that the authenticated content (narrative plus multimedia) is a faithful and complete rendering of the clinical content of the structured source data. As a general guideline, a generated narrative block should include the same human readable content that would be available to users viewing that content in the originating system. Although content formatting in the narrative block need not be identical to that in the originating system, the narrative block should use elements from the CDA narrative block schema to provide sufficient formatting to support human readability when rendered according to the rules defined in Section Narrative Block (§ 4.3.5 ) of the CDA R2 specification.
By definition, a receiving application cannot assume that all clinical content in a section (i.e., in the narrative block and multimedia) is contained in the structured entries unless the entries in the section have an entry relationship of "DRIV".
Additional specification information for the CDA narrative block can be found in the CDA R2 specification in sections 1.2.1, 1.2.3, 1.3, 1.3.1, 1.3.2, 4.3.4.2, and 6.
