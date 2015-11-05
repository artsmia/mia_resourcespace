<?php
# Swedish
# Language File for ResourceSpace
# -------
# Översättningsfil för huvudprogrammet.
#
# Tilläggsprogram översätts i plugins/*/languages/sv.php
# Webbplatsens innehåll såsom instruktioner och andra skräddarsydda texter är översatta i dbstruct/data_site_text.txt - se även Hantera webbplatsens innehåll (Manage Content)
# Fältvärden översätts (än så länge) i Hantera fältalternativ (Manage Field Options)
# Komponenter som t.ex. JUpload översätts inom respektive projekt
#
# Fraserna har översatts för hand, med hjälp av:
# En befintlig svensk maskinöversättning
# Den norska översättningen (den danska var maskinöversatt)
# Computer Swedens språkwebb: http://cstjanster.idg.se/sprakwebben/
# Svenska datatermgruppen: http://www.datatermgruppen.se/
# Svensk översättning av Gnome: http://live.gnome.org/Swedish/GNOMEOrdlista
# Språkrådet: http://www.sprakradet.se/frågelådan
# Norstedts stora engelsk-svenska ordbok
# Nationalencyklopedins ordbok
#
# Mer information om den svenska översättningen finns på sidan:
# http://wiki.resourcespace.org/index.php/Swedish_Translation_-_svensk_%C3%B6vers%C3%A4ttning
# Där finns bland annat de skrivregler och den ordlista som används internt i ResourceSpace
#
# En första version av översättningen skapades av Henrik Frizén (förnamn.efternamn utan accenttecken i e-postboxen.Sveriges landskod) 20110124 för version 2295
#
# Senast uppdaterad av [Namn] [Datum] för version [svn-version], [kommentar]
# Senast uppdaterad av Henrik Frizén 20140312 för version 5361

#
#
# User group names (for the default user groups)
$lang["usergroup-administrators"]="Administratörer";
$lang["usergroup-general_users"]="Vanliga användare";
$lang["usergroup-super_admin"]="Systemadministratör";
$lang["usergroup-archivists"]="Arkivarier";
$lang["usergroup-restricted_user_-_requests_emailed"]="Begränsade – begäranden: e-post";
$lang["usergroup-restricted_user_-_requests_managed"]="Begränsade – begäranden: hanterade";
$lang["usergroup-restricted_user_-_payment_immediate"]="Begränsade – direktbetalning";
$lang["usergroup-restricted_user_-_payment_invoice"]="Begränsade – fakturabetalning";

# Resource type names (for the default resource types)
$lang["resourcetype-photo"]="Fotografi";
$lang["resourcetype-document"]="Dokument";
$lang["resourcetype-video"]="Video";
$lang["resourcetype-audio"]="Audio";
$lang["resourcetype-global_fields"]="Globala fält";
$lang["resourcetype-archive_only"]="Arkiverat material";
$lang["resourcetype-photo-2"]="Fotografier";
$lang["resourcetype-document-2"]="Dokument";
$lang["resourcetype-video-2"]="Videor";
$lang["resourcetype-audio-2"]="Audior";

# Image size names (for the default image sizes)
$lang["imagesize-thumbnail"]="Miniatyrbild";
$lang["imagesize-preview"]="Förhandsgranskning";
$lang["imagesize-screen"]="Skärmbild";
$lang["imagesize-low_resolution_print"]="Lågupplöst utskrift";
$lang["imagesize-high_resolution_print"]="Högupplöst utskrift";
$lang["imagesize-collection"]="Samling";

# Field titles (for the default fields)
$lang["fieldtitle-keywords"]="Nyckelord";
$lang["fieldtitle-country"]="Land";
$lang["fieldtitle-title"]="Titel";
$lang["fieldtitle-story_extract"]=$lang["storyextract"]="Sammanfattning";
$lang["fieldtitle-credit"]="Skapare";
$lang["fieldtitle-date"]=$lang["date"]="Datum";
$lang["fieldtitle-expiry_date"]="Utgångsdatum";
$lang["fieldtitle-caption"]="Beskrivning";
$lang["fieldtitle-notes"]="Anteckningar";
$lang["fieldtitle-named_persons"]="Namngivna personer";
$lang["fieldtitle-camera_make_and_model"]="Kameratillverkare/modell";
$lang["fieldtitle-original_filename"]="Ursprungligt filnamn";
$lang["fieldtitle-video_contents_list"]="Videoinnehållslista";
$lang["fieldtitle-source"]="Källa";
$lang["fieldtitle-website"]="Webbplats";
$lang["fieldtitle-artist"]="Artist";
$lang["fieldtitle-album"]="Album";
$lang["fieldtitle-track"]="Spår";
$lang["fieldtitle-year"]="Årtal";
$lang["fieldtitle-genre"]="Genre";
$lang["fieldtitle-duration"]="Längd";
$lang["fieldtitle-channel_mode"]="Ljudkanaler";
$lang["fieldtitle-sample_rate"]="Samplingsfrekvens";
$lang["fieldtitle-audio_bitrate"]="Bithastighet, ljud";
$lang["fieldtitle-frame_rate"]="Bildfrekvens";
$lang["fieldtitle-video_bitrate"]="Bithastighet, video";
$lang["fieldtitle-aspect_ratio"]="Bildformat";
$lang["fieldtitle-video_size"]="Bildstorlek";
$lang["fieldtitle-image_size"]="Bildstorlek";
$lang["fieldtitle-extracted_text"]="Automatiskt utdrag";
$lang["fieldtitle-file_size"]=$lang["filesize"]="Filstorlek";
$lang["fieldtitle-category"]="Kategori";
$lang["fieldtitle-subject"]="Ämne";
$lang["fieldtitle-author"]="Författare";
$lang["fieldtitle-owner"]="Ägare";

# Field types
$lang["fieldtype-text_box_single_line"]="Textfält (enradigt)";
$lang["fieldtype-text_box_multi-line"]="Textfält (flerradigt)";
$lang["fieldtype-text_box_large_multi-line"]="Textfält (stort flerradigt)";
$lang["fieldtype-text_box_formatted_and_ckeditor"]="Textfält (formaterat)";
$lang["fieldtype-check_box_list"]="Kryssrutor (grupp)";
$lang["fieldtype-drop_down_list"]="Rullgardinslista";
$lang["fieldtype-date"]="Datum";
$lang["fieldtype-date_and_optional_time"]="Datum och eventuell tid";
$lang["fieldtype-date_and_time"]="Datum/tid";
$lang["fieldtype-expiry_date"]="Utgångsdatum";
$lang["fieldtype-category_tree"]="Kategoriträd";
$lang["fieldtype-dynamic_keywords_list"]="Dynamisk nyckelordslista";
$lang["fieldtype-dynamic_tree_in_development"]="Dynamiskt träd (under utveckling)";

# Property labels (for the default properties)
$lang["documentation-permissions"]="Se <a href=../../documentation/permissions_sv.txt target=_blank>hjälpfilen för behörigheter</a> om du behöver mer information.";
$lang["property-reference"]="Referensnr";
$lang["property-name"]="Namn";
$lang["property-permissions"]="Behörigheter";
$lang["information-permissions"]="Obs! Även eventuella globala behörigheter inställda i ’config.php’ gäller.";
$lang["property-fixed_theme"]="Fast tema";
$lang["property-parent"]="Överordnad";
$lang["property-search_filter"]="Sökfilter";
$lang["property-edit_filter"]="Redigeringsfilter";
$lang["property-resource_defaults"]="Förvald metadata för nytt material";
$lang["property-override_config_options"]="Åsidosätt inställningar i ’config.php’";
$lang["property-email_welcome_message"]="Välkomstmeddelande som skickas per e-post";
$lang["information-ip_address_restriction"]="Jokertecken kan användas i begränsningen av ip-adresser (t.ex. 128.124.*).";
$lang["property-ip_address_restriction"]="Begränsning av ip-adresser";
$lang["property-request_mode"]="Läge för begäranden/beställningar";
$lang["property-allow_registration_selection"]="Tillåt val av denna grupp vid registrering";

$lang["property-resource_type_id"]="Materialtypnr";
$lang["information-allowed_extensions"]="Om du vill begränsa vilka typer av filer som ska kunna överföras för denna materialtyp, anger du här de <i>tillåtna</i> filnamnsändelserna (t.ex. jpg, gif).";
$lang["property-allowed_extensions"]="Tillåtna filnamnsändelser";

$lang["property-field_id"]="Fältnr";
$lang["property-title"]="Namn";
$lang["property-resource_type"]="Materialtyp";
$lang["property-field_type"]="Fälttyp";

$lang["property-options"]="Alternativ";
$lang["property-required"]="Obligatoriskt";
$lang["property-order_by"]="Sorteringsnummer";
$lang["property-indexing"]="<b>Indexering</b>";
$lang["information-if_you_enable_indexing_below_and_the_field_already_contains_data-you_will_need_to_reindex_this_field"]="Om du aktiverar indexering nedan och fältet redan innehåller data måste du <a target=_blank href=../tools/reindex_field.php?field=%ref>återindexera detta fält.</a>"; # %ref will be replaced with the field id
$lang["property-index_this_field"]="Indexera detta fält";
$lang["information-enable_partial_indexing"]="Nyckelordsindexering av delar av ord (prefix + infix) bör användas sparsamt, då det ökar storleken på indexet betydligt. Du kan läsa mer om detta i wikin.";
$lang["property-enable_partial_indexing"]="Aktivera indexering av delar av ord";
$lang["information-shorthand_name"]="Obs! Fältet måste ha ett kortnamn för att det ska visas i Avancerad sökning. Kortnamnet får bara innehålla små bokstäver – inga mellanslag, siffror eller specialtecken.";
$lang["property-shorthand_name"]="Kortnamn";
$lang["property-display_field"]="Visa fält";
$lang["property-enable_advanced_search"]="Aktivera i Avancerad sökning";
$lang["property-enable_simple_search"]="Aktivera i Enkel sökning";
$lang["property-use_for_find_similar_searching"]="Använd vid sökning efter liknande material";
$lang["property-iptc_equiv"]="IPTC-motsv.";
$lang["property-display_template"]="Visningsmall";
$lang["property-value_filter"]="Värdefilter";
$lang["property-regexp_filter"]="Reguljärt uttryck för indatakontroll";
$lang["information-regexp_filter"]="Indatakontroll med hjälp av reguljärt uttryck – t.ex. medför uttrycket ”[A-Z]+” att endast stora bokstäver kan användas.";
$lang["information-regexp_fail"]="Det inmatade värdet är inte i det format som krävs.";
$lang["property-tab_name"]="Fliknamn";
$lang["property-smart_theme_name"]="Namn på smart tema";
$lang["property-exiftool_field"]="Exiftool-fält (tag name)";
$lang["property-exiftool_filter"]="Exiftool-filter";
$lang["property-help_text"]="Hjälptext";
$lang["property-tooltip_text"]="Inforuta";
$lang["information-tooltip_text"]="Inforuta: Text som visas i Enkel/Avancerad sökning när muspekaren förs över fältet.";
$lang["information-display_as_dropdown"]="För kryssrutor och rullgardinslistor: Visa fältet som en rullgardinslista i Avancerad sökning? Den förvalda inställningen är att istället visa fält av denna typ som en grupp av kryssrutor för att möjliggöra ELLER-funktion vid sökning.";
$lang["property-display_as_dropdown"]="Visa som rullgardinslista";
$lang["property-external_user_access"]="Tillåt åtkomst för externa användare";
$lang["property-autocomplete_macro"]="Autoförslagsmakro";
$lang["property-hide_when_uploading"]="Dölj vid överföring";
$lang["property-hide_when_restricted"]="Dölj för användare med begränsad åtkomst";
$lang["property-omit_when_copying"]="Utelämna vid kopiering";
$lang["property-sync_with_field"]="Synkronisera med fält";
$lang["information-copy_field"]="<a href=field_copy.php?ref=%ref>Kopiera fält</a>";
$lang["property-display_condition"]="Visningsvillkor";
$lang["information-display_condition"]="Visningsvillkor: Det här fältet visas endast om angivna villkor är uppfyllda. Samma format som för sökfilter för grupper används, det vill säga kortnamn=värde1|värde2, kortnamnA=giltigtalternativA;kortnamnB=giltigtalternativB1|giltigtalternativB2";
$lang["property-onchange_macro"]="Makro vid ändring";
$lang["information-onchange_macro"]="Makro vid ändring: Kod som ska exekveras när fältets värde ändras. FÖRSIKTIGHET REKOMMENDERAS!";

$lang["property-query"]="Fråga";

$lang["information-id"]="Obs! Fältet Id måste innehålla en unik kod bestående av tre bokstäver.";
$lang["property-id"]="Id";
$lang["property-width"]="Bredd";
$lang["property-height"]="Höjd";
$lang["property-pad_to_size"]="Fyll ut till storlek";
$lang["property-internal"]="Intern";
$lang["property-allow_preview"]="Tillåt förhandsgranskning";
$lang["property-allow_restricted_download"]="Tillåt hämtning för användare med begränsad åtkomst";

$lang["property-total_resources"]="Totalt antal material";
$lang["property-total_keywords"]="Totalt antal nyckelord";
$lang["property-resource_keyword_relationships"]="Antal relationer material – nyckelord";
$lang["property-total_collections"]="Totalt antal samlingar";
$lang["property-collection_resource_relationships"]="Antal relationer samling – material";
$lang["property-total_users"]="Totalt antal användare";


# Top navigation bar (also reused for page titles)
$lang["logout"]="Logga ut";
$lang["contactus"]="Kontakta oss";
# next line
$lang["home"]="Startsida";
$lang["searchresults"]="Sökresultat";
$lang["themes"]="Teman";
$lang["mycollections"]="Mina samlingar";
$lang["myrequests"]="Mina begäranden";
$lang["collections"]="Samlingar";
$lang["mycontributions"]="Mina bidrag";
$lang["researchrequest"]="Researchförfrågning";
$lang["helpandadvice"]="Hjälp och tips";
$lang["teamcentre"]="Administration";
# footer link
$lang["aboutus"]="Om oss";
$lang["interface"]="Gränssnitt";

# Search bar
$lang["simplesearch"]="Enkel sökning";
$lang["searchbutton"]="Sök";
$lang["clearbutton"]="Rensa";
$lang["bycountry"]="Efter land";
$lang["bydate"]="Efter datum";
$lang["anyyear"]="Alla år";
$lang["anymonth"]="Alla månader";
$lang["anyday"]="Alla dagar";
$lang["anycountry"]="Alla länder";
$lang["resultsdisplay"]="Resultatvisning";
$lang["xlthumbs"]="Extrastora";
$lang["xlthumbstitle"]="Extrastora miniatyrbilder";
$lang["largethumbs"]="Stora";
$lang["largethumbstitle"]="Stora miniatyrbilder";
$lang["smallthumbs"]="Små";
$lang["smallthumbstitle"]="Små miniatyrbilder";
$lang["list"]="Lista";
$lang["listtitle"]="Lista";
$lang["perpage"]="per sida";

$lang["gotoadvancedsearch"]="Avancerad sökning";
$lang["viewnewmaterial"]="Visa nyaste materialet";
$lang["researchrequestservice"]="Researchförfrågan";

# Team Centre
$lang["manageresources"]="Hantera material";
$lang["overquota"]="Lagringskvoten är överskriden – du kan inte lägga till material";
$lang["managearchiveresources"]="Hantera arkivmaterial";
$lang["managethemes"]="Hantera teman";
$lang["manageresearchrequests"]="Hantera researchförfrågningar";
$lang["manageusers"]="Hantera användare";
$lang["managecontent"]="Hantera webbplatsens innehåll";
$lang["viewstatistics"]="Visa statistik";
$lang["viewreports"]="Visa rapporter";
$lang["viewreport"]="Visa rapport";
$lang["treeobjecttype-report"]=$lang["report"]="Rapport";
$lang["sendbulkmail"]="Gör massutskick";
$lang["systemsetup"]="Systemkonfiguration";
$lang["usersonline"]="Uppkopplade användare (inaktiv tid i minuter)";
$lang["diskusage"]="Använt lagringsutrymme";
$lang["available"]="tillgängligt";
$lang["used"]="använt";
$lang["free"]="ledigt";
$lang["editresearch"]="Redigera researchförfrågan";
$lang["editproperties"]="Redigera egenskaper";
$lang["selectfiles"]="Välj filer";
$lang["searchcontent"]="Sök innehåll";
$lang["ticktodeletehelp"]="Om du vill ta bort detta textavsnitt (på alla språk) markerar du kryssrutan och klickar på <b>Spara</b>";
$lang["createnewhelp"]="Skapa ett nytt hjälpavsnitt";
$lang["searchcontenteg"]="(sida, namn, text)";
$lang["copyresource"]="Kopiera material";
$lang["resourceidnotfound"]="Materialnumret hittades inte";
$lang["inclusive"]="(inklusive)";
$lang["pluginssetup"]="Hantera tillägg";
$lang["pluginmanager"]="Tilläggshanteraren";
$lang["users"]="användare";


# Team Centre - Bulk E-mails
$lang["emailrecipients"]="Mottagare";
$lang["emailsubject"]="Ämne";
$lang["emailtext"]="Meddelande";
$lang["emailhtml"]="Aktivera stöd för html – meddelandet måste använda html-formatering";
$lang["send"]="Skicka";
$lang["emailsent"]="E-postmeddelandet har skickats.";
$lang["mustspecifyoneuser"]="Du måste ange minst en användare";
$lang["couldnotmatchusers"]="Ett eller flera användarnamn är felaktigt eller dubblerat";

# Team Centre - User management
$lang["comments"]="Kommentarer";

# Team Centre - Resource management
$lang["viewuserpending"]="Visa material som väntar på granskning";
$lang["userpending"]="Material som väntar på granskning";
$lang["viewuserpendingsubmission"]="Visa material som är under registrering";
$lang["userpendingsubmission"]="Material som är under registrering";
$lang["searcharchivedresources"]="Sök i arkiverat material";
$lang["viewresourcespendingarchive"]="Visa material som väntar på arkivering";
$lang["resourcespendingarchive"]="Material som väntar på arkivering";
$lang["uploadresourcebatch"]="Överför material";
$lang["uploadinprogress"]="Överföring och skapande av förhandsgranskningar pågår";
$lang["donotmoveaway"]="OBS! Lämna inte den här sidan innan överföringen har slutförts.";
$lang["pleaseselectfiles"]="Välj en eller flera filer att överföra.";
$lang["previewstatus"]="Skapat förhandsgranskningar för material %file% av %filestotal%."; # %file%, %filestotal% will be replaced, e.g. Created previews for resource 2 of 2.
$lang["uploadedstatus"]="Överfört material %file% av %filestotal% – %path%"; # %file%, %filestotal% and %path% will be replaced, e.g. Resource 2 of 2 uploaded - pub/pictures/astro-images/JUPITER9.JPG
$lang["upload_failed_for_path"]="Överföringen misslyckades för %path%"; # %path% will be replaced, e.g. Upload failed for abc123.jpg
$lang["uploadcomplete"]="Överföringen är slutförd";
$lang["upload_summary"]="Sammanfattning av överföringen";
$lang["resources_uploaded-0"]="0 material överfördes korrekt.";
$lang["resources_uploaded-1"]="1 material överfördes korrekt.";
$lang["resources_uploaded-n"]="%done% material överfördes korrekt."; # %done% will be replaced, e.g. 17 resources uploaded OK.
$lang["resources_failed-0"]="0 överföringar misslyckades.";
$lang["resources_failed-1"]="1 överföring misslyckades.";
$lang["resources_failed-n"]="%failed% överföringar misslyckades."; # %failed% will be replaced, e.g. 2 resources failed.
$lang["specifyftpserver"]="Ange ftp-server";
$lang["ftpserver"]="Ftp-server";
$lang["ftpusername"]="Användarnamn (ftp)";
$lang["ftppassword"]="Lösenord (ftp)";
$lang["ftpfolder"]="Mapp (ftp)";
$lang["connect"]="Anslut";
$lang["uselocalupload"]="ELLER: Använd en lokal överföringsmapp i stället för ftp-server";

# User contributions
$lang["contributenewresource"]="Bidra med nytt material";
$lang["viewcontributedps"]="Visa mina bidrag – under registrering";
$lang["viewcontributedpr"]="Visa mina bidrag – väntande på granskning";
$lang["viewcontributedsubittedl"]="Visa mina bidrag – aktiva";
$lang["contributedps"]="Mina bidrag – under registrering";
$lang["contributedpr"]="Mina bidrag – väntande på granskning";
$lang["contributedsubittedl"]="Mina bidrag – aktiva";

# Collections
$lang["editcollection"]="Redigera samling";
$lang["editcollectionresources"]="Redigera samlingens förhandsgranskningar";
$lang["access"]="Åtkomst";
$lang["private"]="Privat";
$lang["public"]="Gemensam";
$lang["attachedusers"]="Tillknutna användare";
$lang["themecategory"]="Temakategori";
$lang["theme"]="Tema";
$lang["newcategoryname"]="… eller ange ett nytt temakategorinamn";
$lang["allowothersaddremove"]="Tillåt andra användare att lägga till/avlägsna material";
$lang["resetarchivestatus"]="Uppdatera status för alla material i samlingen";
$lang["editallresources"]="Redigera alla material i samlingen";
$lang["editresources"]="Redigera material";
$lang["multieditnotallowed"]="Materialen är inte möjliga att redigera i grupp – alla material har inte samma status eller är inte av samma typ.";
$lang["emailcollectiontitle"]="Dela samling via e-post";
$lang["collectionname"]="Samlingsnamn";
$lang["collection-name"]="Samling: %collectionname%"; # %collectionname will be replaced, e.g. Collection: Cars
$lang["collectionid"]="Samlingsnr";
$lang["collectionidprefix"]="Saml_nr";
$lang["_dupe"]="_dubblett";
$lang["emailtousers"]="Mottagare<br><br><b>För mottagare med användarkonto:</b> Ange några bokstäver i användarens namn för att söka, klicka sedan på den hittade användaren och därefter på <b>+</b><br><br><b>För mottagare utan användarkonto:</b> Ange en e-postadress och klicka på <b>+</b>";
$lang["removecollectionareyousure"]="Vill du avlägsna den här samlingen från listan?";
$lang["managemycollections"]="Hantera Mina samlingar";
$lang["createnewcollection"]="Skapa ny samling";
$lang["findpubliccollection"]="Gemensamma samlingar";
$lang["searchpubliccollections"]="Sök gemensamma samlingar";
$lang["addtomycollections"]="Lägg till i Mina samlingar";
$lang["action-addtocollection"]="Lägg till i samling";
$lang["action-removefromcollection"]="Avlägsna från samling";
$lang["addtocollection"]="Lägg till i samling";
$lang["cantmodifycollection"]="Du kan inte ändra på denna samling.";
$lang["currentcollection"]="Aktuell samling";
$lang["viewcollection"]="Visa samling";
$lang["viewall"]="Visa alla";
$lang["action-editall"]="Redigera alla";
$lang["hidethumbnails"]="Dölj miniatyrbilder";
$lang["showthumbnails"]="Visa miniatyrbilder";
$lang["toggle"]="Växla";
$lang["resize"]="Ändra storlek";
$lang["contactsheet"]="Kontaktkopia";
$lang["mycollection"]="Min samling";
$lang["editresearchrequests"]="Redigera researchförfrågningar";
$lang["research"]="Research";
$lang["savedsearch"]="Sparad sökning";
$lang["mustspecifyoneusername"]="Du måste ange minst ett användarnamn";
$lang["couldnotmatchallusernames"]="Ett användarnamn är felaktigt";
$lang["emailcollectionmessage"]="har skickat en samling med material till dig från $applicationname. Denna samling har lagts till i Mina samlingar."; # suffixed to user name e.g. "Fred has e-mailed you a collection..."
$lang["nomessage"]="Inget meddelande";
$lang["emailcollectionmessageexternal"]="har skickat en samling med material till dig från $applicationname."; # suffixed to user name e.g. "Fred has e-mailed you a collection..."
$lang["clicklinkviewcollection"]="Klicka på länken nedan om du vill visa samlingen.";
$lang["zippedcollectiontextfile"]="Inkludera en textfil med information om material/samling";
$lang["archivesettings"]="Arkivinställningar";
$lang["archive-zip"]="Zip";
$lang["archive-7z"]="7z";
$lang["download-of-collections-not-enabled"]="Hämtning av samlingar är inte aktiverad.";
$lang["archiver-utility-not-found"]="Kan inte hitta arkiveringsverktyget.";
$lang["collection_download_settings-not-defined"]="\$collection_download_settings är inte definierad.";
$lang["collection_download_settings-not-an-array"]="\$collection_download_settings är inte en matris.";
$lang["listfile-argument-not-defined"]="\$archiver_listfile_argument är inte definierad.";
$lang["nothing_to_download"]="Inget att hämta.";
$lang["copycollectionremoveall"]="Avlägsna alla material innan kopiering";
$lang["purgeanddelete"]="Rensa ut";
$lang["purgecollectionareyousure"]="Vill du ta bort både den här samlingen och alla material i den?";
$lang["collectionsdeleteempty"]="Ta bort tomma samlingar";
$lang["collectionsdeleteemptyareyousure"]="Vill du ta bort alla dina tomma samlingar?";
$lang["collectionsnothemeselected"]="Du måste antingen välja en befintlig temakategori eller namnge en ny.";
$lang["downloaded"]="Hämtad";
$lang["contents"]="Innehåll";
$lang["forthispackage"]="för det här paketet";
$lang["didnotinclude"]="Utelämnades";
$lang["selectcollection"]="Välj samling";
$lang["total"]="Totalt";
$lang["ownedbyyou"]="ägda av dig";
$lang["edit_theme_category"]="Redigera temakategori";
$lang["emailthemecollectionmessageexternal"]="har skickat samlingar med material från $applicationname till dig per e-post.";
$lang["emailthememessage"]="har skickat ett urval av teman från $applicationname till dig per e-post. Dessa teman har lagts till i Mina samlingar.";
$lang["clicklinkviewthemes"]="Klicka på länken nedan om du vill visa temana.";
$lang["clicklinkviewcollections"]="Klicka på länkarna nedan om du vill visa samlingarna.";

# Lightbox
$lang["lightbox-image"] = "Bild";
$lang["lightbox-of"] = "av";

# Resource create / edit / view
$lang["createnewresource"]="Skapa nytt material";
$lang["treeobjecttype-resource_type"]=$lang["resourcetype"]="Materialtyp";
$lang["resourcetypes"]="Materialtyper";
$lang["deleteresource"]="Ta bort material";
$lang["downloadresource"]="Hämta material";
$lang["rightclicktodownload"]="Högerklicka på denna länk och välj <b>Spara&nbsp;mål&nbsp;som</b> för att hämta materialet."; # For Opera/IE browsers only
$lang["downloadinprogress"]="Hämtning pågår";
$lang["editmultipleresources"]="Redigera material i grupp";
$lang["editresource"]="Redigera material";
$lang["resources_selected-1"]="1 material valt"; # 1 resource selected
$lang["resources_selected-2"]="%number material valda"; # e.g. 17 resources selected
$lang["image"]="Bild";
$lang["previewimage"]="Förhandsgranska bild";
$lang["file"]="Fil";
$lang["upload"]="Överföring";
$lang["action-upload"]="Överför";
$lang["action-upload-to-collection"]="Överför till den här samlingen";
$lang["uploadafile"]="Överför en fil";
$lang["replacefile"]="Ersätt fil";
$lang["imagecorrection"]="Redigera förhandsgranskningar";
$lang["previewthumbonly"]="(endast förhandsgranskning/miniatyrbild)";
$lang["rotateclockwise"]="Rotera medurs";
$lang["rotateanticlockwise"]="Rotera moturs";
$lang["increasegamma"]="Ljusa upp förhandsgranskningar";
$lang["decreasegamma"]="Mörka ner förhandsgranskningar";
$lang["restoreoriginal"]="Återställ original";
$lang["recreatepreviews"]="Återskapa förhandsgranskningar";
$lang["retrypreviews"]="Försök skapa förhandsgranskningar igen";
$lang["specifydefaultcontent"]="Ange den metadata som ska vara förvald för nya material";
$lang["properties"]="– typspecifika egenskaper";
$lang["relatedresources"]="Relaterade material";
$lang["relatedresources-filename_extension"]="Relaterade material – %extension"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "Related resources - %EXTENSION" -> "Related resources - JPG"
$lang["relatedresources-id"]="Relaterade material – nr %id%"; # %id% will be replaced, e.g. Related Resources - ID57
$lang["relatedresources-restype"]="Relaterade material – %restype%"; # Use %RESTYPE%, %restype% or %Restype% as a placeholder. The placeholder will be replaced with the resource type in plural, using the same case. E.g. "Related resources - %restype%" -> "Related resources - photos"
$lang["indexedsearchable"]="Indexerade, sökbara fält";
$lang["clearform"]="Rensa formulär";
$lang["similarresources"]="liknande material"; # e.g. 17 similar resources
$lang["similarresource"]="liknande material"; # e.g. 1 similar resource
$lang["nosimilarresources"]="Inget liknande material";
$lang["emailresourcetitle"]="E-posta material";
$lang["resourcetitle"]="Materialtitel";
$lang["requestresource"]="Begär material";
$lang["action-viewmatchingresources"]="Visa matchande material";
$lang["nomatchingresources"]="Inget matchande material";
$lang["matchingresources"]="matchande material"; # e.g. 17 matching resources
$lang["advancedsearch"]="Avancerad sökning";
$lang["archiveonlysearch"]="Arkiverat material";
$lang["allfields"]="Alla fält";
$lang["typespecific"]="Typspecifika";
$lang["youfound"]="Du hittade"; # e.g. you found 17 resources
$lang["youfoundresources"]="material"; # e.g. you found 17 resources
$lang["youfoundresource"]="material"; # e.g. you found 1 resource
$lang["youfoundresults"]="resultat"; # e.g. you found 17 results
$lang["youfoundresult"]="resultat"; # e.g. you found 1 result
$lang["display"]="Visning"; # e.g. Display: thumbnails / list
$lang["sortorder"]="Sorteringsordning";
$lang["relevance"]="Relevans";
$lang["asadded"]="Tilläggsdatum";
$lang["popularity"]="Popularitet";
$lang["rating"]="Betyg";
$lang["colour"]="Färg";
$lang["jumptopage"]="Gå till sida";
$lang["jump"]="Gå";
$lang["titleandcountry"]="Titel/land";
$lang["torefineyourresults"]="För att förfina resultatet, prova";
$lang["verybestresources"]="De bästa materialen";
$lang["addtocurrentcollection"]="Lägg till i aktuell samling";
$lang["addresource"]="Lägg till ett material";
$lang["addresourcebatch"]="Lägg till material i grupp";
$lang["fileupload"]="Överför fil";
$lang["clickbrowsetolocate"]="Leta upp en fil genom att klicka på <b>Bläddra</b>";
$lang["resourcetools"]="Materialverktyg";
$lang["fileinformation"]="Filinformation";
$lang["options"]="Alternativ";
$lang["previousresult"]="Föregående resultat";
$lang["viewallresults"]="Visa alla resultat";
$lang["nextresult"]="Nästa resultat";
$lang["pixels"]="pixlar";
$lang["download"]="Hämtning";
$lang["preview"]="Förhandsgranskning";
$lang["fullscreenpreview"]="Förhandsgranska på bildskärm";
$lang["originalfileoftype"]="Originalfil (%extension)"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "Original %EXTENSION File" -> "Original PDF File"
$lang["fileoftype"]="?-fil"; # ? will be replaced, e.g. "MP4 File"
$lang["cell-fileoftype"]="%Extension-fil"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "%EXTENSION File" -> "JPG File"
$lang["field-fileextension"]="%Extension"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "%EXTENSION" -> "JPG"
$lang["fileextension-inside-brackets"]="[%extension]"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "[%EXTENSION]" -> "[JPG]"
$lang["fileextension"]="%extension"; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "%EXTENSION" -> "JPG"
$lang["log"]="Logg";
$lang["resourcedetails"]="Egenskaper för material";
$lang["offlineresource"]="Frånkopplat material";
$lang["action-request"]="Begär";
$lang["request"]="Begäran";
$lang["requestlog"]="Begäranslogg";
$lang["searchforsimilarresources"]="Sök efter liknande material";
$lang["clicktoviewasresultset"]="Visa dessa material som ett resultatsätt";
$lang["searchnomatches"]="Sökningen gav inga resultat.";
$lang["try"]="Prova";
$lang["tryselectingallcountries"]="Prova att välja <i>Alla länder</i> i landsfältet eller";
$lang["tryselectinganyyear"]="Prova att välja <i>Alla år</i> i årsfältet eller";
$lang["tryselectinganymonth"]="Prova att välja <i>Alla månader</i> i månadsfältet eller";
$lang["trybeinglessspecific"]="Prova att vara mindre specifik genom";
$lang["enteringfewerkeywords"]="att ange färre sökord."; # Suffixed to any of the above 4 items e.g. "Try being less specific by entering fewer search keywords"
$lang["match"]="träff";
$lang["matches"]="träffar";
$lang["inthearchive"]="i arkivet";
$lang["nomatchesinthearchive"]="Inga träffar i arkivet";
$lang["savethissearchtocollection"]="Lägg till denna sökfråga i aktuell samling";
$lang["mustspecifyonekeyword"]="Du måste ange minst ett sökord.";
$lang["hasemailedyouaresource"]="har skickat ett material till dig per e-post."; # Suffixed to user name, e.g. Fred has e-mailed you a resource
$lang["clicktoviewresource"]="Klicka på länken nedan om du vill visa materialet.";
$lang["statuscode"]="Statuskod";
$lang["unoconv_pdf"]="genererad av Open office";
$lang['calibre_pdf']="genererad av Calibre";
$lang["resourcenotfound"]="Materialet hittades inte.";

# Resource log - actions
$lang["resourcelog"]="Materiallogg";
$lang["log-u"]="Överförde fil";
$lang["log-c"]="Skapade material";
$lang["log-d"]="Hämtade fil";
$lang["log-e"]="Redigerade fält";
$lang["log-m"]="Redigerade fält (i grupp)";
$lang["log-E"]="Delade material via e-post till";//  + notes field
$lang["log-v"]="Visade material";
$lang["log-x"]="Tog bort material";
$lang["log-l"]="Loggade in"; # For user entries only.
$lang["log-t"]="Transformerade fil";
$lang["log-s"]="Ändrade status";
$lang["log-a"]="Ändrade åtkomst";
$lang["log-r"]="Återställde metadata";
$lang["log-b"]="Skapade alternativ fil";
$lang["log-missinglang"]="[type] (lang saknas)"; # [type] will be replaced.

$lang["backtoresourceview"]="Tillbaka: Visa material";
$lang["continuetoresourceview"]="Fortsätt: Visa material";

# Resource status
$lang["status"]="Status"; # Ska kunna inleda med "Materialet är" direkt följt av statusen.
$lang["status-2"]="Under registrering";
$lang["status-1"]="Väntande på granskning";
$lang["status0"]="Aktivt";
$lang["status1"]="Väntande på arkivering";
$lang["status2"]="Arkiverat";
$lang["status3"]="Borttaget";

# Charts
$lang["activity"]="Aktivitet";
$lang["summary"]="– sammanfattning";
$lang["mostinaday"]="Störst antal på en dag";
$lang["totalfortheyear"]="Totalt antal hittills i år";
$lang["totalforthemonth"]="Totalt antal under innevarande månad";
$lang["dailyaverage"]="Dagligt genomsnittligt antal för denna period";
$lang["nodata"]="Inga uppgifter för denna period.";
$lang["max"]="Max"; # i.e. maximum
$lang["statisticsfor"]="Statistik för"; # e.g. Statistics for 2007
$lang["printallforyear"]="Skriv ut all statistik för året";

# Log in / user account
$lang["nopassword"]="Klicka här om du vill ansöka om ett användarkonto";
$lang["forgottenpassword"]="Klicka här om du har glömt ditt lösenord";
$lang["keepmeloggedin"]="Håll mig inloggad på den här datorn";
$lang["columnheader-username"]=$lang["username"]="Användarnamn";
$lang["password"]="Lösenord";
$lang["login"]="Logga in";
$lang["loginincorrect"]="Det angivna användarnamnet eller lösenordet är fel.<br/><br/>Klicka på länken ovan<br/>om du vill begära ett nytt lösenord.";
$lang["accountexpired"]=">Användarkontots utgångsdatum har passerats. Kontakta systemets administratör.";
$lang["useralreadyexists"]="Ett användarkonto med samma e-postadress eller användarnamn existerar redan, ändringarna har inte sparats";
$lang["useremailalreadyexists"]="Ett användarkonto med samma e-postadress existerar redan.";
$lang["ticktoemail"]="Skicka användarnamnet och ett nytt lösenord till denna användare";
$lang["ticktodelete"]="Om du vill ta bort denna användare markerar du kryssrutan och klickar på <b>Spara</b>";
$lang["edituser"]="Redigera användare";
$lang["columnheader-full_name"]=$lang["fullname"]="Fullständigt namn";
$lang["email"]="E-post";
$lang["columnheader-e-mail_address"]=$lang["emailaddress"]="E-postadress";
$lang["suggest"]="Föreslå";
$lang["accountexpiresoptional"]="Användarkontot går ut<br/>(ej obligatoriskt)";
$lang["lastactive"]="Senast aktiv";
$lang["lastbrowser"]="Senaste använd webbläsare";
$lang["searchusers"]="Sök användare";
$lang["createuserwithusername"]="Skapa användare med användarnamn";
$lang["emailnotfound"]="E-postadressen kunde inte hittas";
$lang["yourname"]="Ditt fullständiga namn";
$lang["youremailaddress"]="Din e-postadress";
$lang["sendreminder"]="Skicka påminnelse";
$lang["sendnewpassword"]="Skicka nytt lösenord";
$lang["requestuserlogin"]="Ansök om ett användarkonto";
$lang["accountlockedstatus"]="Användarkontot är låst";
$lang["accountunlock"]="Lås upp";

# Research request
$lang["nameofproject"]="Projektets namn";
$lang["descriptionofproject"]="Beskrivning av projektet";
$lang["descriptionofprojecteg"]="(t.ex. målgrupp, stil, ämne eller geografiskt område)";
$lang["deadline"]="Tidsfrist";
$lang["nodeadline"]="Ingen tidsfrist";
$lang["noprojectname"]="Du måste ange ett namn på projektet";
$lang["noprojectdescription"]="Du måste ange en beskrivning av projektet";
$lang["contacttelephone"]="Kontakttelefon";
$lang["finaluse"]="Slutanvändning";
$lang["finaluseeg"]="(t.ex. PowerPoint, broschyr eller affisch)";
$lang["noresourcesrequired"]="Mängd material som krävs för den färdiga produkten";
$lang["shaperequired"]="Önskad bildorientering";
$lang["portrait"]="Porträtt";
$lang["landscape"]="Landskap";
$lang["square"]="Kvadratisk";
$lang["either"]="Valfri";
$lang["sendrequest"]="Skicka förfrågan";
$lang["editresearchrequest"]="Redigera researchförfrågan";
$lang["requeststatus0"]=$lang["unassigned"]="Ej tilldelad";
$lang["requeststatus1"]="Under behandling";
$lang["requeststatus2"]="Besvarad";
$lang["copyexistingresources"]="Kopiera materialen i en befintlig samling till denna research";
$lang["deletethisrequest"]="Om du vill ta bort denna begäran markerar du kryssrutan och klickar på <b>Spara</b>";
$lang["requestedby"]="Inskickad av";
$lang["requesteditems"]="Begärt material";
$lang["assignedtoteammember"]="Tilldelad gruppmedlem";
$lang["typecollectionid"]="(ange samlingsnumret nedan)";
$lang["researchid"]="Researchförfrågenr";
$lang["assignedto"]="Tilldelad";
$lang["createresearchforuser"]="Skapa researchförfrågan för användare";
$lang["searchresearchrequests"]="Sök researchförfrågan";
$lang["requestasuser"]="Förfråga som användare";
$lang["haspostedresearchrequest"]="har postat en researchförfrågan"; # username is suffixed to this
$lang["newresearchrequestwaiting"]="Ny researchförfrågan väntar";
$lang["researchrequestassignedmessage"]="Din researchförfrågan har tilldelats en medlem i teamet. När vi har slutfört researchen kommer du att få ett e-postmeddelande med en länk till de material som vi rekommenderar.";
$lang["researchrequestassigned"]="Researchförfrågan är tilldelad";
$lang["researchrequestcompletemessage"]="Din researchförfrågan är besvarad och materialet har lagts till i Mina samlingar.";
$lang["researchrequestcomplete"]="Besvarad researchförfrågan";


# Misc / global
$lang["selectgroupuser"]="Välj grupp/användare…";
$lang["select"]="Välj…";
$lang["selectloading"]="Välj...";
$lang["add"]="Lägg till";
$lang["create"]="Skapa";
$lang["treeobjecttype-group"]=$lang["group"]="Grupp";
$lang["confirmaddgroup"]="Vill du lägga till alla medlemmar i den här gruppen?";
$lang["backtoteamhome"]="Tillbaka: Administration, första sidan";
$lang["columnheader-resource_id"]=$lang["resourceid"]="Materialnr";
$lang["id"]="Nr";
$lang["todate"]="Till datum";
$lang["fromdate"]="Från datum";
$lang["day"]="Dag";
$lang["month"]="Månad";
$lang["year"]="År";
$lang["hour-abbreviated"]="TT";
$lang["minute-abbreviated"]="MM";
$lang["itemstitle"]="Poster";
$lang["tools"]="Verktyg";
$lang["created"]="Skapad";
$lang["user"]="Användare";
$lang["owner"]="Ägare";
$lang["message"]="Meddelande";
$lang["name"]="Namn";
$lang["action"]="Handling";
$lang["treeobjecttype-field"]=$lang["field"]="Fält";
$lang["save"]="Spara";
$lang["revert"]="Återställ";
$lang["cancel"]="Avbryt";
$lang["view"]="Visa";
$lang["type"]="Typ";
$lang["text"]="Text";
$lang["yes"]="Ja";
$lang["no"]="Nej";
$lang["key"]="Symbolförklaring:"; # e.g. explanation of icons on search page
$lang["languageselection"]="Språkval";
$lang["language"]="Språk";
$lang["changeyourpassword"]="Byt lösenord";
$lang["yourpassword"]="Lösenord";
$lang["currentpassword"]="Nuvarande lösenord";
$lang["newpassword"]="Nytt lösenord";
$lang["newpasswordretype"]="Nytt lösenord (repetera)";
$lang["passwordnotvalid"]="Detta är inte ett giltigt lösenord";
$lang["passwordnotmatch"]="Det upprepade lösenordet matchar inte lösenordet";
$lang["wrongpassword"]="Lösenordet är fel, försök igen";
$lang["action-view"]="Visa";
$lang["action-preview"]="Förhandsgranska";
$lang["action-expand"]="Expandera";
$lang["action-select"]="Välj";
$lang["action-download"]="Hämta";
$lang["action-email"]="E-posta";
$lang["action-edit"]="Redigera";
$lang["action-delete"]="Ta bort";
$lang["action-deletecollection"]="Ta bort samling";
$lang["action-revertmetadata"]="Återställ metadata";
$lang["confirm-revertmetadata"]="Vill du återextrahera den ursprungliga metadatan ur den här filen? Om du väljer att fortsätta simuleras en ny överföring av filen och därmed förloras all ändrad metadata.";
$lang["action-remove"]="Avlägsna";
$lang["complete"]="Slutförd";
$lang["backtohome"]="Tillbaka: Startsida";
$lang["continuetohome"]="Fortsätt: Startsida";
$lang["backtohelphome"]="Tillbaka: Hjälp och tips, första sidan";
$lang["backtosearch"]="Tillbaka: Sökresultat";
$lang["backtoview"]="Tillbaka: Visa material";
$lang["backtoeditresource"]="Tillbaka: Redigera material";
$lang["backtouser"]="Tillbaka: Välkommen till ResourceSpace";
$lang["continuetouser"]="Fortsätt: Välkommen till ResourceSpace";
$lang["termsandconditions"]="Användningsvillkor";
$lang["iaccept"]="Jag accepterar";
$lang["contributedby"]="Tillagt av";
$lang["format"]="Format";
$lang["notavailableshort"]="–";
$lang["allmonths"]="Alla månader";
$lang["allgroups"]="Alla grupper";
$lang["status-ok"]="Okej";
$lang["status-fail"]="MISSLYCKADES";
$lang["status-warning"]="VARNING";
$lang["status-notinstalled"]="Ej installerad";
$lang["status-never"]="Aldrig";
$lang["softwareversion"]="?-version"; # E.g. "PHP version"
$lang["softwarebuild"]="?-bygge"; # E.g. "ResourceSpace Build"
$lang["softwarenotfound"]="Programmet ’?’ hittades inte."; # ? will be replaced.
$lang["client-encoding"]="(klientkodning: %encoding)"; # %encoding will be replaced, e.g. client-encoding: utf8
$lang["browseruseragent"]="Webbläsare";
$lang['serverplatform']="Serverplattform";
$lang["are_available-0"]="är tillgängliga";
$lang["are_available-1"]="är tillgängligt";
$lang["are_available-2"]="är tillgängliga";
$lang["were_available-0"]="var tillgängliga";
$lang["were_available-1"]="var tillgängligt";
$lang["were_available-2"]="var tillgängliga";
$lang["resource-0"]="material";
$lang["resource-1"]="material";
$lang["resource-2"]="material";
$lang["status-note"]="OBSERVERA";
$lang["action-changelanguage"]="Byt språk";
$lang["loading"]="Läser in …";

# Pager
$lang["next"]="Nästa";
$lang["previous"]="Föregående";
$lang["page"]="Sida";
$lang["of"]="av"; # e.g. page 1 of 2
$lang["items"]="poster"; # e.g. 17 items
$lang["item"]="post"; # e.g. 1 item

# Statistics
$lang["stat-addpubliccollection"]="Tillägg av gemensamma samlingar"; # Det ska vara möjligt att sätta "Antal" framför alla aktiviteter.
$lang["stat-addresourcetocollection"]="Tillägg av material i samlingar";
$lang["stat-addsavedsearchtocollection"]="Tillägg av sparade sökningar i samlingar";
$lang["stat-addsavedsearchitemstocollection"]="Tillägg av poster från sparade sökningar i samlingar";
$lang["stat-advancedsearch"]="Avancerade sökningar";
$lang["stat-archivesearch"]="Arkivsökningar";
$lang["stat-assignedresearchrequest"]="Tilldelade researchförfrågningar";
$lang["stat-createresource"]="Skapade material";
$lang["stat-e-mailedcollection"]="E-postutskick av samlingar";
$lang["stat-e-mailedresource"]="E-postutskick av material";
$lang["stat-keywordaddedtoresource"]="Tillägg av nyckelord till material";
$lang["stat-keywordusage"]="Användningar av nyckelord";
$lang["stat-newcollection"]="Nya samlingar";
$lang["stat-newresearchrequest"]="Nya researchförfrågningar";
$lang["stat-printstory"]="Utskrifter av sammanfattningar";
$lang["stat-processedresearchrequest"]="Besvarade researchförfrågningar";
$lang["stat-resourcedownload"]="Hämtningar av material";
$lang["stat-resourceedit"]="Redigeringar av material";
$lang["stat-resourceupload"]="Överföringar av material";
$lang["stat-resourceview"]="Visningar av material";
$lang["stat-search"]="Sökningar";
$lang["stat-usersession"]="Användarsessioner";
$lang["stat-addedsmartcollection"]="Tillägg av smarta samlingar";

# Access
$lang["access0"]="Öppen";
$lang["access1"]="Begränsad";
$lang["access2"]="Konfidentiell";
$lang["access3"]="Anpassad";
$lang["statusandrelationships"]="Status och relationer";

# Lists
$lang["months"]=array("januari","februari","mars","april","maj","juni","juli","augusti","september","oktober","november","december");
$lang["false-true"]=array("Falskt","Sant");

# Formatting
$lang["plugin_field_fmt"]="%A (%B)"; // %A and %B are replaced by content defined by individual plugins. See, e.e., config_db_single_select in /include/plugin_functions.php


#Sharing
$lang["share"]="Dela";
$lang["sharecollection"]="Dela samling";
$lang["sharecollection-name"]="Dela samling – %collectionname"; # %collectionname will be replaced, e.g. Share Collection - Cars
$lang["share_theme_category"]="Dela temakategori";
$lang["share_theme_category_subcategories"]="Inkludera teman i subkategorier för externa användare?";
$lang["email_theme_category"]="E-posta temakategori";
$lang["generateurl"]="Generera webbadress";
$lang["generateurls"]="Generera webbadresser";
$lang["generateexternalurl"]="Generera extern webbadress";
$lang["generateexternalurls"]="Generera externa webbadresser";
$lang["generateurlinternal"]="Nedanstående webbadress fungerar bara för inloggade användare.";
$lang["generateurlexternal"]="Nedanstående webbadress fungerar för alla och kräver inte inloggning.";
$lang["generatethemeurlsexternal"]="Nedanstående webbadresser fungerar för alla och kräver inte inloggning.";
$lang["showexistingthemeshares"]="Visa existerande delningar för teman i den här kategorin";
$lang["internalusersharing"]="Delning med interna användare";
$lang["externalusersharing"]="Delning med externa användare";
$lang["externalusersharing-name"]="Delning med externa användare – %collectionname%"; # %collectionname will be replaced, e.g. External User Sharing - Cars
$lang["accesskey"]="Åtkomstnyckel";
$lang["sharedby"]="Delad av";
$lang["sharedwith"]="Delad med";
$lang["lastupdated"]="Senast uppdaterad";
$lang["lastused"]="Senast använd";
$lang["noattachedusers"]="Ingen tillknuten användare.";
$lang["confirmdeleteaccess"]="Vill du ta bort den här åtkomstnyckeln? Om du väljer att fortsätta kommer användare som har fått tillgång till samlingen med hjälp av denna nyckel inte längre att kunna komma åt samlingen.";
$lang["confirmdeleteaccessresource"]="Vill du ta bort den här åtkomstnyckeln? Om du väljer att fortsätta kommer användare som har fått tillgång till materialet med hjälp av denna nyckel inte längre att kunna komma åt materialet.";
$lang["noexternalsharing"]="Ingen extern delning.";
$lang["sharedcollectionaddwarning"]="Varning! Denna samling delas med externa användare. Det material som du har lagt till har därmed gjorts tillgängligt för dessa användare. Klicka på Dela samling om du vill hantera den externa åtkomsten för denna samling.";
$lang["sharedcollectionaddwarningupload"]="Varning! Den valda samlingen delas med externa användare. De material som du lägger till kommer därmed att göras tillgängliga för dessa användare. Klicka på Dela samling i samlingspanelen om du vill hantera den externa åtkomsten för denna samling.";
$lang["restrictedsharecollection"]="Delning är inte tillåten eftersom du har begränsad åtkomst till minst ett material i den här samlingen.";
$lang["selectgenerateurlexternal"]="Om du vill skapa en extern webbadress som fungerar för användare utan användarkonto, anger du först den åtkomstnivå som du finner lämplig.";
$lang["selectgenerateurlexternalthemecat"]="Om du vill skapa externa webbadresser som fungerar för användare utan användarkonto, anger du först den åtkomstnivå som du finner lämplig.";
$lang["externalselectresourceaccess"]="Om du delar material med en användare utan användarkonto väljer du en åtkomstnivå som du finner lämplig";
$lang["externalselectresourceexpires"]="Om du delar material med en användare utan användarkonto väljer du ett utgångsdatum för den genererade webbadressen";
$lang["externalshareexpired"]="Delningens utgångsdatum har passerats och därför är delningen inte längre tillgänglig.";
$lang["notapprovedsharecollection"]="Ett eller flera material i denna samling är inte aktiva och därför är delning inte möjlig.";
$lang["notapprovedsharetheme"]="Delning är inte möjlig för åtminstone en av samlingarna eftersom ett eller flera material inte är aktiva.";
$lang["notapprovedresources"]="Följande material är inte aktiva och kan därför inte läggas till i en delad samling: ";


# New for 1.3
$lang["savesearchitemstocollection"]="Lägg till sökresultatet i aktuell samling";
$lang["removeallresourcesfromcollection"]="Om du vill avlägsna alla material från denna samling markerar du kryssrutan och klickar på <b>Spara</b>";
$lang["deleteallresourcesfromcollection"]="Om du vill ta bort själva materialen som ingår i denna samling markerar du kryssrutan och klickar på <b>Spara</b>";
$lang["deleteallsure"]="Vill du ta bort de här materialen? Om du väljer att fortsätta tas själva materialen bort, de avlägsnas inte bara från denna samling.";
$lang["batchdonotaddcollection"]="(Lägg inte till i någon samling)";
$lang["collectionsthemes"]="Relaterade teman och gemensamma samlingar";
$lang["recent"]="Nyaste";
$lang["n_recent"]="%qty nyaste";
$lang["batchcopyfrom"]="Kopiera metadata från material med nummer";
$lang["copy"]="Kopiera";
$lang["zipall"]="Hämta samling";
$lang["downloadzip"]="Hämta samlingen som ett arkiv";
$lang["downloadsize"]="Hämtningsstorlek";
$lang["tagging"]="Taggning";
$lang["speedtagging"]="Snabbtaggning";
$lang["existingkeywords"]="Befintliga nyckelord:";
$lang["extrakeywords"]="Extra nyckelord";
$lang["leaderboard"]="Rankningstabell";
$lang["confirmeditall"]="Vill du spara? Om du väljer att fortsätta skrivs existerande värden över, för de valda fälten, i alla material i den aktuella samlingen.";
$lang["confirmsubmitall"]="Vill du sända alla material till granskning? Om du väljer att fortsätta skrivs existerande värden över, för de valda fälten, i alla material i den aktuella samlingen, därefter sänds materialen till granskning.";
$lang["confirmunsubmitall"]="Vill du dra tillbaka alla material från granskningsprocessen? Om du väljer att fortsätta skrivs existerande värden över, för de valda fälten, i alla material i den aktuella samlingen, därefter dras materialen tillbaka från granskningsprocessen.";
$lang["confirmpublishall"]="Vill du publicera materialen? Om du väljer att fortsätta skrivs existerande värden över, för de valda fälten, i alla material i den aktuella samlingen, därefter publiceras materialen för gemensam visning.";
$lang["confirmunpublishall"]="Vill du dra tillbaka publiceringen? Om du väljer att fortsätta skrivs existerande värden över, för de valda fälten, i alla material i den aktuella samlingen, därefter dras materialen tillbaka från gemensam visning.";
$lang["collectiondeleteconfirm"]="Vill du ta bort den här samlingen?";
$lang["hidden"]="(Dolt)";
$lang["requestnewpassword"]="Begär nytt lösenord";

# New for 1.4
$lang["reorderresources"]="Klicka och dra om du vill ändra ordningen på materialen inom samlingen";
$lang["addorviewcomments"]="Skriv eller visa kommentarer";
$lang["collectioncomments"]="Samlingskommentarer";
$lang["collectioncommentsinfo"]="Skriv en kommentar till materialet. Kommentaren gäller bara i den här samlingen.";
$lang["comment"]="Kommentar";
$lang["warningexpired"]="Materialets utgångsdatum har passerats";
$lang["warningexpiredtext"]="Varning! Materialets utgångsdatum har passerats. Du måste klicka på länken nedan för att aktivera hämtning av material.";
$lang["warningexpiredok"]="&gt; Aktivera hämtning av material";
$lang["userrequestcomment"]="Meddelande";
$lang["addresourcebatchbrowser"]="Lägg till material i grupp – i webbläsare";
$lang["addresourcebatchbrowserjava"]="Lägg till material i grupp – i webbläsare (Java – äldre)";

$lang["addresourcebatchftp"]="Lägg till material i grupp – överför från ftp-server";
$lang["replaceresourcebatch"]="Ersätt material i grupp";
$lang["editmode"]="Redigeringsläge";
$lang["replacealltext"]="Ersätt befintlig text";
$lang["findandreplace"]="Sök och ersätt";
$lang["prependtext"]="Lägg till text före";
$lang["appendtext"]="Lägg till text efter";
$lang["removetext"]="Ta bort text";
$lang["find"]="Sök";
$lang["andreplacewith"]="… och ersätt med …";
$lang["relateallresources"]="Skapa relationer mellan alla material i den här samlingen";

# New for 1.5
$lang["columns"]="Kolumner";
$lang["contactsheetconfiguration"]="Inställningar för kontaktkopia";
$lang["thumbnails"]="Miniatyrbilder";
$lang["contactsheetintrotext"]="Välj inställningar för kontaktkopian. Förhandsgranskningen uppdateras automatiskt när du ändrar inställningar. Om du ändrar ordning på materialen måste du dock klicka på <b>Förhandsgranska</b> för att uppdatera förhandsgranskningen.<br />Klicka på <b>Skapa</b> när du vill generera och hämta kontaktkopian i pdf-format.";
$lang["size"]="Storlek";
$lang["orientation"]="Orientering";
$lang["requiredfield"]="Obligatoriskt fält";
$lang["requiredfields"]="Alla obligatoriska fält är inte ifyllda. Gå igenom formuläret och prova sedan igen.";
$lang["viewduplicates"]="Visa dubbletter av material";
$lang["duplicateresources"]="Dubbletter av material";
$lang["userlog"]="Användarlogg";
$lang["ipaddressrestriction"]="Begränsa tillåtna ip-adresser<br/>(ej obligatoriskt)";
$lang["wildcardpermittedeg"]="Jokertecken är tillåtna, t.ex.";

# New for 1.6
$lang["collection_download_original"]="Originalfil";
$lang["newflag"]="NY!";
$lang["link"]="Länk";
$lang["uploadpreview"]="Överför en bild som ny förhandsgranskning";
$lang["starttypingusername"]="Användarnamn, namn eller gruppnamn…";
$lang["requestfeedback"]="Begär respons<br/>(svar sänds per e-post)";
$lang["sendfeedback"]="Skicka respons";
$lang["feedbacknocomments"]="Du har inte gett någon respons på materialen i samlingen.<br/>Klicka på pratbubblorna bredvid materialen när du vill ge respons.";
$lang["collectionfeedback"]="Respons på samlingen";
$lang["collectionfeedbackemail"]="Du har fått följande respons:";
$lang["feedbacksent"]="Din respons har skickats.";
$lang["newarchiveresource"]="Lägg till ett arkiverat material";
$lang["nocategoriesselected"]="Inga kategorier valda";
$lang["showhidetree"]="Visa/dölj träd";
$lang["clearall"]="Rensa alla";
$lang["clearcategoriesareyousure"]="Vill du rensa alla valda alternativ?";

$lang["archive"]="Arkiv";
$lang["collectionviewhover"]="Visa materialen som ingår i samlingen.";
$lang["collectioncontacthover"]="Skapa en kontaktkopia med de material som ingår i samlingen.";
$lang["original"]="Original";

$lang["password_not_min_length"]="Lösenordet måste innehålla minst ? tecken";
$lang["password_not_min_alpha"]="Lösenordet måste innehålla minst ? bokstäver (a–z, A–Z)";
$lang["password_not_min_uppercase"]="Lösenordet måste innehålla minst ? versaler (A–Z)";
$lang["password_not_min_numeric"]="Lösenordet måste innehålla minst ? siffror (0–9)";
$lang["password_not_min_special"]="Lösenordet måste innehålla minst ? icke alfanumeriska tecken (!@$%&amp;* etc.)";
$lang["password_matches_existing"]="Det föreslagna lösenordet är samma som det befintliga lösenordet";
$lang["password_expired"]="Ditt lösenords utgångsdatum har passerats och du måste nu ange ett nytt lösenord";
$lang["max_login_attempts_exceeded"]="Du har överskridit det maximalt tillåtna antalet inloggningsförsök. Du måste nu vänta ? minuter innan du kan försöka logga in igen.";

$lang["newlogindetails"]="Du hittar dina nya inloggningsuppgifter nedan."; # For new password mail
$lang["youraccountdetails"]="Dina kontouppgifter"; # Subject of mail sent to user on user details save

$lang["copyfromcollection"]="Kopiera från samling";
$lang["donotcopycollection"]="Kopiera inte från en samling";

$lang["resourcesincollection"]="material i den här samlingen"; # E.g. 3 resources in this collection
$lang["removefromcurrentcollection"]="Avlägsna från aktuell samling";
$lang["showtranslations"]="+ Visa översättningar";
$lang["hidetranslations"]="&minus; Dölj översättningar";
$lang["archivedresource"]="Arkiverat material";

$lang["managerelatedkeywords"]="Hantera relaterade nyckelord";
$lang["keyword"]="Nyckelord";
$lang["relatedkeywords"]="Relaterade nyckelord";
$lang["matchingrelatedkeywords"]="Matchande relaterade nyckelord";
$lang["newkeywordrelationship"]="Skapa ny relation för nyckelord";
$lang["searchkeyword"]="Sök nyckelord";

$lang["exportdata"]="Exportera data";
$lang["exporttype"]="Exportformat";

$lang["managealternativefiles"]="Hantera alternativa filer";
$lang["managealternativefilestitle"]="Hantera alternativa filer";
$lang["alternativefiles"]="Alternativa filer";
$lang["filetype"]="Filtyp";
$lang["filedeleteconfirm"]="Vill du ta bort den här filen?";
$lang["addalternativefile"]="Lägg till alternativ fil";
$lang["editalternativefile"]="Redigera alternativ fil";
$lang["description"]="Beskrivning";
$lang["notuploaded"]="Inte överförda";
$lang["uploadreplacementfile"]="Överför ersättningsfil";
$lang["backtomanagealternativefiles"]="Tillbaka: Hantera alternativa filer";


$lang["resourceistranscoding"]="Detta material kodas just nu om";
$lang["cantdeletewhiletranscoding"]="Du kan inte ta bort material medan det kodas om";

$lang["maxcollectionthumbsreached"]="Det finns för många material i den här samlingen för att kunna visa miniatyrbilder. Miniatyrbilderna kommer nu därför att döljas.";

$lang["ratethisresource"]="Vilket betyg ger du det här materialet?";
$lang["ratingthankyou"]="Tack för ditt betyg!";
$lang["ratings"]="betyg";
$lang["rating_lowercase"]="betyg";
$lang["ratingremovehover"]="Ta bort ditt betyg";
$lang["ratingremoved"]="Ditt betyg har tagits bort.";

$lang["cannotemailpassword"]="Du kan inte skicka användarna deras existerande lösenord, eftersom de är lagrade i krypterad form.<br/><br/>Klicka på <b>Föreslå</b> om du vill generera ett nytt lösenord, som sedan kan skickas per e-post.";

$lang["userrequestnotification1"]="Användarformuläret har fyllts i med följande uppgifter:";
$lang["userrequestnotification2"]="Om du godtar denna ansökan, kan du gå till webbadressen nedan och skapa ett användarkonto för denna användaren.";
$lang["ipaddress"]="Ip-adress";
$lang["userresourcessubmitted"]="Följande användarbidrag har lagts fram för granskning:";
$lang["userresourcesapproved"]="Dina inskickade material har godkänts:";
$lang["userresourcesunsubmitted"]="Följande användarbidrag har dragits tillbaka och kräver inte längre granskning:";
$lang["viewalluserpending"]="Visa alla användarbidrag som väntar på granskning:";

# New for 1.7
$lang["installationcheck"]="Installationskontroll";
$lang["managefieldoptions"]="Hantera fältalternativ";
$lang["matchingresourcesheading"]="Matchande material";
$lang["backtofieldlist"]="Tillbaka: Fältlistan";
$lang["rename"]="Byt namn";
$lang["showalllanguages"]="Visa alla språk";
$lang["hidealllanguages"]="Dölj alla språk";
$lang["clicktologinasthisuser"]="Klicka här om du vill logga in som denna användare";
$lang["addkeyword"]="Lägg till nyckelord";
$lang["selectedresources"]="Valda material";
$lang["addresourcebatchlocalfolder"]="Lägg till material i grupp – överfrån från lokal mapp";
$lang["phpextensions"]="PHP-utökningar";

# Setup Script
$lang["setup-alreadyconfigured"]="Installationen av ResourceSpace är redan konfigurerad. Om du vill göra om konfigurationen tar du bort <pre>’include/config.php’</pre> och pekar webbläsaren till den här sidan igen.";
$lang["setup-successheader"]="Gratulerar!";
$lang["setup-successdetails"]="Den grundläggande delen av installationen av ResourceSpace är nu slutförd. Gå igenom filen ’include/default.config.php’ om du vill se fler konfigurationsmöjligheter.";
$lang["setup-successnextsteps"]="Nästa steg:";
$lang["setup-successremovewrite"]="Du bör nu avlägsna skrivrättigheten till katalogen ’include/’.";
$lang["setup-visitwiki"]='Besök <a target="_blank" href="http://wiki.resourcespace.org/index.php/?title=main_Page">ResourceSpace Documentation Wiki</a> (engelskspråkig wiki) om du vill hitta mer information om hur du anpassar din installation.';
$lang["php-config-file"]="Konfiguration för php: '%phpinifile'"; # %phpinifile will be replaced, e.g. PHP config: '/etc/php5/apache2/php.ini'
$lang["setup-checkconfigwrite"]="Skrivrättighet till konfigurationskatalog:";
$lang["setup-checkstoragewrite"]="Skrivrättighet till lagringskatalog:";
$lang["setup-welcome"]="Välkommen till ResourceSpace";
$lang["setup-introtext"]="Tack för att du väljer ResourceSpace. Detta konfigurationsskript hjälper dig att installera ResourceSpace. Det behöver endast göras en gång.";
$lang["setup-checkerrors"]="Fel upptäcktes i systemkonfigurationen.<br/>Åtgärda dessa fel, och peka sedan webbläsaren till den här sidan igen när du vill fortsätta.";
$lang["setup-errorheader"]="Fel upptäcktes i konfigurationen. Se detaljerade felmeddelanden nedan.";
$lang["setup-warnheader"]="Några av inställningarna genererade varningsmeddelanden, se nedan. Det betyder inte nödvändigtvis att det är ett problem med konfigurationen.";
$lang["setup-basicsettings"]="Grundläggande inställningar";
$lang["setup-basicsettingsdetails"]="Här gör du de grundläggande inställningarna för installationen av ResourceSpace.<br><strong>*</strong>Obligatoriskt fält";
$lang["setup-dbaseconfig"]="Databaskonfiguration";
$lang["setup-mysqlerror"]="Det finns ett fel i MySQL-inställningarna:";
$lang["setup-mysqlerrorversion"]="MySQL-versionen måste vara 5 eller senare.";
$lang["setup-mysqlerrorserver"]="Kunde inte ansluta till servern.";
$lang["setup-mysqlerrorlogin"]="Inloggningen misslyckades. Kontrollera användarnamn och lösenord.";
$lang["setup-mysqlerrordbase"]="Kunde inte att ansluta till databasen.";
$lang["setup-mysqlerrorperns"]="Kunde inte skapa tabeller. Kontrollera databasanvändarens behörigheter.";
$lang["setup-mysqltestfailed"]="Testet misslyckades (kunde inte verifiera MySQL).";
$lang["setup-mysqlserver"]="MySQL-server:";
$lang["setup-mysqlusername"]="Användarnamn (MySQL):";
$lang["setup-mysqlpassword"]="Lösenord (MySQL):";
$lang["setup-mysqldb"]="Databasnamn (MySQL):";
$lang["setup-mysqlbinpath"]="Programsökväg (MySQL):";
$lang["setup-generalsettings"]="Allmänna inställningar";
$lang["setup-baseurl"]="Baswebbadress:";
$lang["setup-emailfrom"]="E-post skickas från adress:";
$lang["setup-emailnotify"]="E-post skickas till adress:";
$lang["setup-spiderpassword"]="Spindellösenord:";
$lang["setup-scramblekey"]="Skramlingsnyckel:";
$lang["setup-apiscramblekey"]="Skramlingsnyckel för api:et:";
$lang["setup-paths"]="Sökvägar";
$lang["setup-pathsdetail"]="Ange sökväg, utan efterföljande snedstreck, för varje program. Lämna sökvägen tom för att inaktivera ett program. En del sökvägar har upptäckts och fyllts i automatiskt.";
$lang["setup-applicationname"]="Programmets namn:";
$lang["setup-basicsettingsfooter"]="Obs! Alla <strong>obligatoriska</strong> inställningar är samlade på den här sidan. Om du inte är intresserad av att kontrollera de avancerade inställningarna kan du klicka på <b>Starta&nbsp;installation</b>.";
$lang["setup-if_mysqlserver"]="Ip-adressen eller <abbr title=\"Fullständigt kvalificerat domännamn\">fqdn</abbr> för MySQL-servern. Ange ’localhost’ om MySQL är installerad på samma server som webbservern.";
$lang["setup-if_mysqlusername"]="Användarnamnet som ska användas för att ansluta till MySQL-servern. Användaren måste ha rättighet att skapa tabeller i databasen.";
$lang["setup-if_mysqlpassword"]="Lösenordet för MySQL-användaren.";
$lang["setup-if_mysqldb"]="Namnet på MySQL-databasen som ResourceSpace ska använda. Databasen måste redan existera.";
$lang["setup-if_mysqlbinpath"]="Sökvägen till MySQL-klientens programfiler, t.ex. mysqldump. Obs! Denna uppgift behövs bara om du avser att använda exportverktyg.";
$lang["setup-if_baseurl"]="Baswebbadressen för den här installationen. Obs! Utan efterföljande snedstreck.";
$lang["setup-if_emailfrom"]="Adressen som e-post från ResourceSpace tycks komma ifrån.";
$lang["setup-if_emailnotify"]="E-postadress som materialbegäranden, kontoansökningar och researchförfrågningar ska skickas till.";
$lang["setup-if_spiderpassword"]="Spindellösenordet är en obligatorisk uppgift.";
$lang["setup-if_scramblekey"]="Ange en sträng att använda som skramlingssnyckel, om du vill aktivera skramling av materialsökvägar. Om det här är en installation nåbar från Internet rekommenderas detta starkt. Om du lämnar fältet tomt inaktiverar du skramling. Innehållet i fältet har redan slumpats fram för dig, men du kan ändra det så att det matchar en befintlig installation. Strängen ska vara svår att gissa – som ett lösenord.";
$lang["setup-if_apiscramblekey"]="Ange en sträng att använda som skramlingsnyckel för api:et. Om du planerar att använda api:er rekommenderas detta starkt.";
$lang["setup-if_applicationname"]="Namnet på implementationen/installationen (ex. MittFöretags mediaarkiv).";
$lang["setup-err_mysqlbinpath"]="Det går inte att verifiera sökvägen. Lämna tomt för att inaktivera.";
$lang["setup-err_baseurl"]="Baswebbadressen är ett obligatoriskt fält.";
$lang["setup-err_baseurlverify"]="Baswebbadressen verkar inte vara korrekt (kunde inte läsa in license.txt).";
$lang["setup-err_spiderpassword"]="Lösenord som krävs för ’spider.php’. VIKTIGT! Slumpa fram ett lösenord för varje ny installation. Allt material kommer att kunna läsas av den som kan detta lösenord. Innehållet i fältet har redan slumpats fram för dig, men du kan ändra det så att det matchar en befintlig installation.";
$lang["setup-err_scramblekey"]="Om installationen är nåbar från Internet rekommenderas skramling starkt.";
$lang["setup-err_apiscramblekey"]="Om installationen är nåbar från Internet rekommenderas skramling starkt.";
$lang["setup-err_path"]="Det går inte att verifiera sökvägen för";
$lang["setup-emailerr"]="Ogiltig e-postadress.";
$lang["setup-rs_initial_configuration"]="ResourceSpace: Inledande konfiguration";
$lang["setup-include_not_writable"]="Skrivrättighet till katalogen ’/include’ saknas. Krävs bara under installationen.";
$lang["setup-override_location_in_advanced"]="Sökvägen kan åsidosättas i Avancerade inställningar.";
$lang["setup-advancedsettings"]="Avancerade inställningar";
$lang["setup-binpath"]="Sökväg till %bin"; #%bin will be replaced, e.g. "Imagemagick Path"
$lang["setup-begin_installation"]="Starta installation";
$lang["setup-generaloptions"]="Allmänna alternativ";
$lang["setup-allow_password_change"]="Tillåt byte av lösenord";
$lang["setup-enable_remote_apis"]="Tillåt api-anrop utifrån";
$lang["setup-if_allowpasswordchange"]="Tillåt användarna att byta sina egna lösenord.";
$lang["setup-if_enableremoteapis"]="Tillåt fjärråtkomst till api-tilläggen.";
$lang["setup-allow_account_requests"]="Tillåt ansökningar om användarkonton";
$lang["setup-display_research_request"]="Visa funktionen researchfrågan";
$lang["setup-if_displayresearchrequest"]="Tillåt användarna att skicka in researchförfrågningar via ett formulär, som sedan skickas per e-post.";
$lang["setup-themes_as_home"]="Använd sidan Teman som startsida";
$lang["setup-remote_storage_locations"]="Platser för fjärrlagring";
$lang["setup-use_remote_storage"]="Använd fjärrlagring";
$lang["setup-if_useremotestorage"]="Markera den här kryssrutan om du vill konfigurera fjärrlagring för ResourceSpace. (För att placera lagringskatalogen på en annan server.)";
$lang["setup-storage_directory"]="Lagringskatalog";
$lang["setup-if_storagedirectory"]="Var materialfilerna lagras. Kan vara en absolut sökväg (/var/www/blah/blah) eller relativ till installationen. Obs! Inget efterföljande snedstreck.";
$lang["setup-storage_url"]="Lagringskatalogens webbadress";
$lang["setup-if_storageurl"]="Var lagringskatalogen finns tillgänglig. Kan vara absolut (http://filer.exempel.se) eller relativ till installationen. Obs! Inget efterföljande snedstreck.";
$lang["setup-ftp_settings"]="Ftp-inställningar";
$lang["setup-if_ftpserver"]="Krävs endast om du planerar att hämta material från en ftp-server.";
$lang["setup-login_to"]="Logga in i";
$lang["setup-configuration_file_output"]="Utmatning till konfigurationsfilen";

# Collection log - actions
$lang["collectionlog"]="Samlingslogg";
$lang["collectionlogheader"]="Samlingslogg – %collection"; # %collection will be replaced, e.g. Collection Log - My Collection
$lang["collectionlog-r"]="Avlägsnade material";
$lang["collectionlog-R"]="Avlägsnade alla material";
$lang["collectionlog-D"]="Tog bort alla material";
$lang["collectionlog-d"]="Tog bort material"; // this shows external deletion of any resources related to the collection.
$lang["collectionlog-a"]="Lade till material";
$lang["collectionlog-c"]="Lade till material (kopierade)";
$lang["collectionlog-m"]="Lade till materialkommentar";
$lang["collectionlog-*"]="Lade till materialbetyg";
$lang["collectionlog-S"]="Delade samlingen med "; //  + notes field
$lang["collectionlog-E"]="Skickade samlingen per e-post till ";//  + notes field
$lang["collectionlog-s"]="Delade material med ";//  + notes field
$lang["collectionlog-T"]="Slutade dela samlingen med ";//  + notes field
$lang["collectionlog-t"]="Återtog åtkomst till material för ";//  + notes field
$lang["collectionlog-X"]="Tog bort samlingen";
$lang["collectionlog-b"]="Transformerade i grupp";
$lang["collectionlog-Z"]="Hämtade samlingen";

$lang["viewuncollectedresources"]="Visa material som inte ingår i samlingar";

# Collection requesting
$lang["requestcollection"]="Begär samling";

# Metadata report
$lang["metadata-report"]="Detaljerad metadata";

# Video Playlist
$lang["videoplaylist"]="Videospellista";

$lang["collection"]="Samling";
$lang["idecline"]="Jag accepterar inte"; # For terms and conditions

$lang["mycollection_notpublic"]="Samlingen ’Min samling’ kan inte göras till en gemensam samling eller ett tema. Skapa en ny samling för dessa ändamål.";

$lang["resourcemetadata"]="Metadata för material";
$lang["columnheader-expires"]=$lang["expires"]="Utgår";
$lang["expires-date"]="Utgår: %date%"; # %date will be replaced, e.g. Expires: Never
$lang["never"]="Aldrig";

$lang["approved"]="Godkänd";
$lang["notapproved"]="Ej godkänd";

$lang["userrequestnotification3"]="Klicka på länken nedan om du vill se över detaljerna och sedan eventuellt godkänna användarkontot.";

$lang["ticktoapproveuser"]="Markera kryssrutan om du vill godkänna användaren och aktivera kontot";

$lang["managerequestsorders"]="Hantera begäranden/beställningar";
$lang["editrequestorder"]="Redigera begäran/beställning";
$lang["requestorderid"]="Begäransnr/beställningsnr";
$lang["viewrequesturl"]="Klicka på länken nedan om du vill visa denna begäran:";
$lang["requestreason"]="Anledning till begäran";

$lang["resourcerequeststatus0"]="Väntande";
$lang["resourcerequeststatus1"]="Bifallen";
$lang["resourcerequeststatus2"]="Avslagen";

$lang["ppi"]="ppi"; # (Pixels Per Inch - used on the resource download options list).

$lang["useasthemethumbnail"]="Vill du använda det här materialet som miniatyrbild för temakategorin?";
$lang["sessionexpired"]="Du har blivit utloggad eftersom du var inaktiv i mer än 30&nbsp;minuter. Logga in igen om du vill fortsätta.";

$lang["resourcenotinresults"]="Detta material ingår inte längre i sökresultatet, navigering till nästa/föregående är därför inte möjlig.";
$lang["publishstatus"]="Spara med publiceringsstatus:";
$lang["addnewcontent"]="Nytt innehåll (sida, namn)";
$lang["hitcount"]="Antal träffar";
$lang["downloads"]="Hämtningar";

$lang["addremove"]="";

##  Translations for standard log entries
$lang["all_users"]="alla användare";
$lang["new_resource"]="nytt material";

$lang["invalidextension_mustbe"]="Ogiltig filnamnsändelse, måste vara";
$lang["invalidextension_mustbe-extensions"]="Ogiltig filnamnsändelse, måste vara %extensions."; # Use %EXTENSIONS, %extensions or %Extensions as a placeholder. The placeholder will be replaced with the filename extensions, using the same case. E.g. "Invalid extension, must be %EXTENSIONS" -> "Invalid extension, must be JPG"
$lang["allowedextensions"]="Giltiga filnamnsändelser";
$lang["allowedextensions-extensions"]="Giltiga filnamnsändelser: %extensions"; # Use %EXTENSIONS, %extensions or %Extensions as a placeholder. The placeholder will be replaced with the filename extensions, using the same case. E.g. "Allowed Extensions: %EXTENSIONS" -> "Allowed Extensions: JPG, PNG"

$lang["alternativebatchupload"]="Överför alternativa filer i grupp";

$lang["confirmdeletefieldoption"]="Vill du ta bort det här fältalternativet?";

$lang["cannotshareemptycollection"]="Denna samling är tom och kan inte delas.";
$lang["cannotshareemptythemecategory"]="Denna temakategori innehåller inga teman och kan inte delas.";

$lang["requestall"]="Begär alla";
$lang["requesttype-email_only"]=$lang["resourcerequesttype0"]="E-post";
$lang["requesttype-managed"]=$lang["resourcerequesttype1"]="Hanterad";
$lang["requesttype-payment_-_immediate"]=$lang["resourcerequesttype2"]="Direktbetalning";
$lang["requesttype-payment_-_invoice"]=$lang["resourcerequesttype3"]="Fakturabetalning";

$lang["requestsent"]="Din materialbegäran har skickats ";
$lang["requestsenttext"]="Din materialbegäran har skickats och kommer att behandlas inom kort.";
$lang["requestupdated"]="Din materialbegäran har uppdaterats ";
$lang["requestassignedtouser"]="Din materialbegäran har tilldelats %.";
$lang["requestapprovedmail"]="Din begäran har blivit bifallen. Klicka på länken nedan om du vill visa och hämta de begärda materialen.";
$lang["requestdeclinedmail"]="Din begäran har blivit avslagen för materialen i samlingen nedan.";

$lang["resourceexpirymail"]="För följande material har utgångsdatumet passerats:";
$lang["resourceexpiry"]="Materialets utgångsdatum";

$lang["requestapprovedexpires"]="Din åtkomst till dessa material går ut den";

$lang["pleasewaitsmall"]="(vänta …)";
$lang["removethisfilter"]="(avlägsna detta filter)";

$lang["no_exif"]="Extrahera inte exif-, IPTC- eller xmp-metadata vid denna överföring";
$lang["difference"]="Skillnad";
$lang["viewdeletedresources"]="Visa borttagna material";
$lang["finaldeletion"]="Detta material är redan markerat som borttaget. Om du fortsätter tas material bort permanent.";
$lang["diskerror"]="Lagringskvoten överskriden";

$lang["nocookies"]="En kaka kunde inte sparas korrekt. Kontrollera att webbläsaren tillåter kakor.";

$lang["selectedresourceslightroom"]="Valda material (lista kompatibel med Adobe Lightroom):";

# Plugins Manager
$lang['plugins-noneinstalled'] = "Inga tillägg aktiverade.";
$lang['plugins-noneavailable'] = "Inga tillägg tillgängliga.";
$lang['plugins-availableheader'] = 'Tillgängliga tillägg';
$lang['plugins-installedheader'] = 'Aktiverade tillägg';
$lang['plugins-author'] = 'Upphovsman';
$lang['plugins-version'] = 'Version';
$lang['plugins-instversion'] = 'Installerad version';
$lang['plugins-uploadheader'] = 'Överför tillägg';
$lang['plugins-uploadtext'] = 'Rsp-fil att överföra';
$lang['plugins-deactivate'] = 'Inaktivera';
$lang['plugins-moreinfo'] = 'Mer information';
$lang['plugins-activate'] = 'Aktivera';
$lang['plugins-purge'] = 'Rensa ut konfiguration';
$lang['plugins-rejmultpath'] = 'Arkivet innehåller flera sökvägar. (Säkerhetsrisk)';
$lang['plugins-rejrootpath'] = 'Arkivet innehåller absoluta sökvägar. (Säkerhetsrisk)';
$lang['plugins-rejparentpath'] = 'Arkivet innehåller överliggande sökvägar (../). (Säkerhetsrisk)';
$lang['plugins-rejmetadata'] = 'Arkivets informationsfil hittades inte.';
$lang['plugins-rejarchprob'] = 'Ett problem uppstod under uppackningen:';
$lang['plugins-rejfileprob'] = 'Tillägget måste vara en rsp-fil.';
$lang['plugins-rejremedy'] = "Om du litar på detta tillägg kan du installera det manuellt genom att packa upp arkivet direkt i katalogen ’plugins’.";
$lang['plugins-uploadsuccess'] = 'Överföringen av tillägget slutfördes korrekt';
$lang['plugins-headertext'] = 'Tillägg kan ge nya funktioner och ny stil till ResourceSpace.';
$lang['plugins-legacyinst'] = 'Aktiverat via ’config.php’';
$lang['plugins-uploadbutton'] = 'Överför tillägg';
$lang['plugins-download'] = 'Hämta&nbsp;inställningar';
$lang['plugins-upload-title'] = 'Överför inställningar från fil';
$lang['plugins-upload'] = 'Överför inställningar';
$lang['plugins-getrsc'] = 'Fil att använda:';
$lang['plugins-saveconfig'] = 'Spara inställningar';
$lang['plugins-saveandexit'] = 'Spara och avsluta';
$lang['plugins-didnotwork'] = 'Ett problem uppstod. Välj en giltig rsc-fil för det här tillägget och klicka på <b>Överför&nbsp;inställningar</b>.';
$lang['plugins-goodrsc'] = 'Inställningarna överförda. Klicka på <b>Spara&nbsp;inställningar</b> om du vill spara inställningarna.';
$lang['plugins-badrsc'] = 'Detta var inte en giltig rsc-fil.';
$lang['plugins-wrongplugin'] = 'Detta var en rsc-fil för tillägget %plugin. Välj en fil för det här tillägget.'; // %plugin is replaced by the name of the plugin being configured.
$lang['plugins-configvar'] = 'Ställer in konfigurationsvariabeln: $%cvn'; //%cvn is replaced by the name of the config variable being set

#Location Data
$lang['location-title'] = 'Platsinformation';
$lang['location-add'] = 'Lägg till plats';
$lang['location-edit'] = 'Redigera plats';
$lang['location-details'] = "Med <b>Dragläge</b> växlar du mellan att positionera nålen och att panorera. Använd zoomkontrollerna för att zooma in och ut. Klicka på <b>Spara</b> för att spara nålposition och zoomnivå.";
$lang['location-noneselected']="Ingen plats vald";
$lang['location'] = 'Plats';
$lang['mapzoom'] = 'Kartzoomning';
$lang['openstreetmap'] = "Openstreetmap";
$lang['google_terrain'] = "Google: Terräng";
$lang['google_default_map'] = "Google: Grundkarta";
$lang['google_satellite'] = "Google: Satellit";
$lang["markers"] = "Markörer";

$lang["publiccollections"]="Gemensamma samlingar";
$lang["viewmygroupsonly"]="Visa bara mina grupper";
$lang["usemetadatatemplate"]="Använd metadatamall";
$lang["undometadatatemplate"]="(ångra val av metadatamall)";

$lang["accountemailalreadyexists"]="Ett användarkonto med samma e-postadress existerar redan";

$lang["backtothemes"]="Tillbaka: Teman";
$lang["downloadreport"]="Hämta rapport";

#Bug Report Page
$lang['reportbug']="Förbered en buggrapport till utvecklarna av ResourceSpace";
$lang['reportbug-detail']="Följande information har sammanställts till buggrapporten. Du kommer i nästa steg att kunna redigera all data innan du skickar in rapporten.";
$lang['reportbug-login']="&gt; Obs! Klicka här för att logga in i bugghanteringssystemet <i>innan</i> du klickar på <b>Förbered&nbsp;buggrapport</b>";
$lang['reportbug-preparebutton']="Förbered buggrapport";

$lang["enterantispamcode"]="<strong>Robotfilter</strong> <sup>*</sup><br />Fyll i koden:";

$lang["groupaccess"]="Gruppåtkomst";
$lang["plugin-groupsallaccess"]="Detta tillägg är aktiverat för alla grupper";
$lang["plugin-groupsspecific"]="Detta tillägg är aktiverat endast för markerade grupper";


$lang["associatedcollections"]="Samlingar som detta material ingår i";
$lang["emailfromuser"]="Skicka e-postmeddelandet från ";
$lang["emailfromsystem"]="Avmarkera kryssrutan om du vill att e-postmeddelandet ska skickas från systemets e-postadress: ";



$lang["previewpage"]="Förhandsgranska sida";
$lang["nodownloads"]="Inga hämtningar";
$lang["uncollectedresources"]="Material som inte ingår i samlingar";
$lang["nowritewillbeattempted"]="Exiftool kommer inte att försöka skriva metadata.";
$lang["notallfileformatsarewritable"]="Exiftool kan dock inte skriva i alla filtyper.";
$lang["filetypenotsupported"]="Filtypen %extension stöds inte."; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "%EXTENSION filetype not supported" -> "JPG filetype not supported"
$lang["exiftoolprocessingdisabledforfiletype"]="Exiftool är inaktiverat för filtypen %extension."; # Use %EXTENSION, %extension or %Extension as a placeholder. The placeholder will be replaced with the filename extension, using the same case. E.g. "Exiftool processing disabled for file type %EXTENSION" -> "Exiftool processing disabled for file type JPG"
$lang["nometadatareport"]="Ingen metadatarapport";
$lang["metadatawritewillbeattempted"]="Exiftool kommer att försöka skriva nedanstående metadata.";
$lang["metadatatobewritten"]="Exiftool kommer att skriva nedanstående metadata";
$lang["embeddedvalue"]="Inbäddat värde";
$lang["exiftooltag"]="Exiftool-fält";
$lang["error"]="Fel";
$lang["exiftoolnotfound"]="Exiftool kunde inte hittas";
$lang["existing_tags"]="Existerande Exiftool-fält";
$lang["new_tags"]="Nya Exiftool-fält (vilka kommer att läggas till vid hämtning)";
$lang["date_of_download"]="[Datum vid nedladdning]";
$lang["field_ref_and_name"]="%ref% – %name%"; # %ref% and %name% will be replaced, e.g. 3 – Country

$lang["indicateusage"]="Beskriv hur du planerar att använda detta material.";
$lang["usage"]="Användning";
$lang["usagecomments"]="Användning";
$lang["indicateusagemedium"]="Användningsmedia";
$lang["usageincorrect"]="Du måste ange hur du planerar att använda materialet samt välja ett media";

$lang["savesearchassmartcollection"]="Spara sökning som en smart samling";
$lang["smartcollection"]="Smart samling";
$lang["dosavedsearch"]="Utför sparad sökning";


$lang["uploadertryjava"]="Använd den äldre Java-överföraren";
$lang["uploadertryplupload"]="<strong>NY!</strong> – Prova nya överföraren.";
$lang["getjava"]="Besök Javas webbplats om du vill säkerställa att du har den senaste Java-versionen installerad";

$lang["all"]="Alla";
$lang["allresourcessearchbar"]="Alla material";
$lang["allcollectionssearchbar"]="Alla samlingar";
$lang["backtoresults"]="Tillbaka: Sökresultat";
$lang["continuetoresults"]="Fortsätt: Sökresultat";

$lang["preview_all"]="Förhandsgranska alla";

$lang["usagehistory"]="Användningshistorik";
$lang["usagebreakdown"]="Detaljerad användningshistorik";
$lang["usagetotal"]="Totalt hämtat";
$lang["usagetotalno"]="Totalt antal hämtningar";
$lang["ok"]="OK";

$lang["random"]="Slumpmässig";
$lang["userratingstatsforresource"]="Användarbetyg för material";
$lang["average"]="Medel";
$lang["popupblocked"]="Poppuppfönstret har blockerats av webbläsaren.";
$lang["closethiswindow"]="Stäng fönstret";

$lang["requestaddedtocollection"]="Detta material har lagts till i den aktuella samlingen. Du kan begära alla poster i samlingen genom att klicka på Begär alla i panelen Mina samlingar i nederkant av skärmen.";

# E-commerce text
$lang["buynow"]="Köp nu";
$lang["yourbasket"]="Din varukorg";
$lang["addtobasket"]="Lägg i varukorg";
$lang["yourbasketisempty"]="Din varukorg är tom.";
$lang["yourbasketcontains-1"]="Din varukorg innehåller 1 artikel.";
$lang["yourbasketcontains-2"]="Din varukorg innehåller %qty artiklar."; # %qty will be replaced, e.g. Your basket contains 3 items.
$lang["buy"]="Köp";
$lang["buyitemaddedtocollection"]="Detta material har lagts i din varukorg. Du kan köpa alla artiklar i din varukorg genom att klicka på Köp nu.";
$lang["buynowintro"]="Välj de storlekar du önskar.";
$lang["nodownloadsavailable"]="Det finns inga hämtningar tillgängliga för detta material.";
$lang["proceedtocheckout"]="Gå till kassan";
$lang["totalprice"]="Totalsumma";
$lang["price"]="Pris";
$lang["waitingforpaymentauthorisation"]="Vi har ännu inte fått betalningsuppdraget. Vänta en kort stund och klicka sedan på <b>Läs&nbsp;om</b>.";
$lang["reload"]="Läs om";
$lang["downloadpurchaseitems"]="Hämta köpta artiklar";
$lang["downloadpurchaseitemsnow"]="Använd länkarna nedan för att hämta dina köpta artiklar direkt.<br><br>Lämna inte den här sidan innan du har hämtat alla artiklar.";
$lang["alternatetype"]="Alternativ typ";
$lang["viewpurchases"]="Mina köp";
$lang["viewpurchasesintro"]="Använd länkarna nedan för att nå tidigare köpta material.";
$lang["orderdate"]="Beställningsdatum";
$lang["removefrombasket"]="Avlägsna från varukorg";
$lang["total-orders-0"] = "<strong>Totalt: 0</strong> beställningar";
$lang["total-orders-1"] = "<strong>Totalt: 1</strong> beställning";
$lang["total-orders-2"] = "<strong>Totalt: %number</strong> beställningar"; # %number will be replaced, e.g. Total: 5 Orders
$lang["purchase_complete_email_admin"] = "Meddelande om köp";
$lang["purchase_complete_email_admin_body"] = "Följande köp har genomförts.";
$lang["purchase_complete_email_user"] = "Bekräftelse av köp";
$lang["purchase_complete_email_user_body"] = "Tack för ditt köp! Använd länkarna nedan för att nå dina köpta artiklar.";


$lang["subcategories"]="Underkategorier";
$lang["subcategory"]="Underkategori";
$lang["back"]="Tillbaka";

$lang["pleasewait"]="Vänta …";

$lang["autorotate"]="Rotera bilder automatiskt";

# Reports
# Report names (for the default reports)
$lang["report-keywords_used_in_resource_edits"]="Nyckelord använda i material";
$lang["report-keywords_used_in_searches"]="Nyckelord använda i sökningar";
$lang["report-resource_download_summary"]="Materialhämtningar – sammanställning";
$lang["report-resource_views"]="Materialvisningar";
$lang["report-resources_sent_via_e-mail"]="Material skickade per e-post";
$lang["report-resources_added_to_collection"]="Material tillagda i samling";
$lang["report-resources_created"]="Material skapade";
$lang["report-resources_with_zero_downloads"]="Material utan hämtningar";
$lang["report-resources_with_zero_views"]="Material utan visningar";
$lang["report-resource_downloads_by_group"]="Materialhämtningar per grupp";
$lang["report-resource_download_detail"]="Materialhämtningar – detaljerad lista";
$lang["report-user_details_including_group_allocation"]="Användaruppgifter inklusive grupptillhörighet";
$lang["report-expired_resources"]="Utgångna material";

#Column headers (for the default reports)
$lang["columnheader-keyword"]="Nyckelord";
$lang["columnheader-entered_count"]="Antal förekomster";
$lang["columnheader-searches"]="Sökningar";
$lang["columnheader-date_and_time"]="Datum/tid";
$lang["columnheader-downloaded_by_user"]="Hämtat av användare";
$lang["columnheader-user_group"]="Grupp";
$lang["columnheader-resource_title"]="Materialtitel";
$lang["columnheader-title"]="Titel";
$lang["columnheader-downloads"]="Hämtningar";
$lang["columnheader-group_name"]="Gruppnamn";
$lang["columnheader-resource_downloads"]="Hämtningar";
$lang["columnheader-views"]="Visningar";
$lang["columnheader-added"]="Tillagt";
$lang["columnheader-creation_date"]="Skapat";
$lang["columnheader-sent"]="Skickat";
$lang["columnheader-last_seen"]="Senast inloggad";

$lang["period"]="Period";
$lang["lastndays"]="Senaste ? dagarna"; # ? is replaced by the system with the number of days, for example "Last 100 days".
$lang["specificdays"]="Specifikt antal dagar";
$lang["specificdaterange"]="Specifik period";
$lang["to"]="till";

$lang["emailperiodically"]="Skapa ett nytt periodiskt återkommande e-postutskick";
$lang["emaileveryndays"]="Skicka mig denna rapport per e-post var ? dag";
$lang["newemailreportcreated"]="Ett nytt periodiskt återkommande e-postutskick har skapats. Om du vill avbryta utskicken klickar du på webblänken som finns nederst i varje meddelande.";
$lang["unsubscribereport"]="Om du vill avbryta prenumerationen på den här rapporten klickar du på webblänken nedan:";
$lang["unsubscribed"]="Prenumerationen avbruten";
$lang["youhaveunsubscribedreport"]="Du har avbrutit prenumerationen på det periodiskt återkommande e-postutskicket med rapporter.";
$lang["sendingreportto"]="Skickar rapporten till";
$lang["reportempty"]="Ingen matchande data hittades för vald rapport och period.";

$lang["purchaseonaccount"]="Debitera konto";
$lang["areyousurepayaccount"]="Vill du debitera ditt konto med detta köp?";
$lang["accountholderpayment"]="Kontobetalning";
$lang["subtotal"]="Delsumma";
$lang["discountsapplied"]="Avdragna rabatter";
$lang["log-p"]="Köpte material";
$lang["viauser"]="via användare";
$lang["close"]="Stäng";

# Installation Check
$lang["repeatinstallationcheck"]="Repetera installationskontroll";
$lang["shouldbeversion"]="Ska vara version ? eller senare"; # E.g. "should be 4.4 or greater"
$lang["phpinivalue"]="Värde i php.ini för ’?’"; # E.g. "PHP.INI value for 'memory_limit'"
$lang["writeaccesstofilestore"]="Skrivrättighet till katalogen ’" . $storagedir ."’ finns?";
$lang["nowriteaccesstofilestore"]="Skrivrättighet till katalogen ’" . $storagedir ."’ saknas.";
$lang["writeaccesstohomeanim"]="Skrivrättighet till katalogen ’" . $homeanim_folder ."’ finns?";
$lang["nowriteaccesstohomeanim"]="Skrivrättighet till katalogen ’" . $homeanim_folder ."’ saknas. Skrivrättighet måste finnas för att tillägget <b>transform</b> ska kunna infoga bilder i startsidans bildspel.";
$lang["blockedbrowsingoffilestore"]="Åtkomsten till katalogen ’filestore’ är blockerad för webbläsare?";
$lang["noblockedbrowsingoffilestore"]="Katalogen ’filestore’ är inte blockerad för webbläsare. Avlägsna ’Indexes’ från ’Options’ i Apache.";
$lang["execution_failed"]="Ett oväntat svar gavs när %command exekverades. Svaret var: '%output'.";  # %command and %output will be replaced, e.g. Execution failed; unexpected output when executing convert command. Output was '[stdout]'.
$lang["exif_extension"]="Exif-utökning";
$lang["archiver_utility"]="Arkiveringsverktyg";
$lang["zipcommand_deprecated"]="\$zipcommand bör inte längre användas. Använd istället \$collection_download och \$collection_download_settings.";
$lang["zipcommand_overridden"]="Notera dock att \$zipcommand är definierat men åsidosatt.";
$lang["lastscheduledtaskexection"]="Senaste körning av schemalagda aktiviteter (dagar)";
$lang["executecronphp"]="Sökningar efter liknande material kommer inte att fungera som de ska och schemalagda e-postrapporter kommer inte att skickas. Se till att <a href='../batch/cron.php'>’batch/cron.php’</a> körs åtminstone en gång per dag som ett cron-jobb eller liknande.";
$lang["shouldbeormore"]="Bör vara ? eller mer."; # E.g. should be 200M or greater
$lang["config_file"]="(konfiguration: %file)"; # %file will be replaced, e.g. config: /etc/php5/apache2/php.ini
$lang['large_file_support_64_bit'] = 'Stöder stora filer (64-bitars plattform)?';
$lang['large_file_warning_32_bit'] = 'VARNING: Kör 32-bitars php. Filer större än 2 GB stöds inte.';

$lang["starsminsearch"]="Antal stjärnor (minimum)";
$lang["anynumberofstars"]="Valfritt antal stjärnor";
$lang["star"]="Stjärna";
$lang["stars"]="Stjärnor";

$lang["noupload"]="Ingen överföring";

# System Setup
# System Setup Tree Nodes (for the default setup tree)
$lang["treenode-root"]="Rot";
$lang["treenode-group_management"]="Grupphanteraren";
$lang["treenode-new_group"]="Ny grupp";
$lang["treenode-new_subgroup"]="Ny undergrupp";
$lang["treenode-resource_types_and_fields"]="Materialtyper/-fält";
$lang["treenode-new_resource_type"]="Ny materialtyp";
$lang["treenode-new_field"]="Nytt fält";
$lang["treenode-reports"]="Rapporter";
$lang["treenode-new_report"]="Ny rapport";
$lang["treenode-downloads_and_preview_sizes"]="Storlekar för hämtning/förhandsgranskning";
$lang["treenode-new_download_and_preview_size"]="Ny storlek";
$lang["treenode-database_statistics"]="Databasstatistik";
$lang["treenode-permissions_search"]="Behörighetssökning";
$lang["treenode-no_name"]="Namnlös";

$lang["treeobjecttype-preview_size"]="Storlek";

$lang["permissions"]="Behörigheter";

# System Setup File Editor
$lang["configdefault-title"]="(Kopiera och klistra in inställningarna härifrån.)";
$lang["config-title"]="Var <i>mycket</i> noga med att undvika syntaxfel. Om du skapar en fil med ett syntaxfel kan systemet bli obrukbart och felet kan då inte korrigeras inifrån ResourceSpace!";

# System Setup Properties Pane
$lang["file_too_large"]="Filen är för stor";
$lang["field_updated"]="Fältet uppdaterat.";
$lang["zoom"]="Förstoring";
$lang["deletion_instruction"]="Lämna tomt och klicka på <b>Spara</b> om du vill ta bort denna fil";
$lang["upload_file"]="Överför fil";
$lang["item_deleted"]="Posten borttagen";
$lang["viewing_version_created_by"]="Visar versionen skapad av";
$lang["on_date"]="den";
$lang["launchpermissionsmanager"]="Starta Behörighetshanteraren";
$lang["confirm-deletion"]="Vill du ta bort denna post?";

# Permissions Manager
$lang["permissionsmanager"]="Behörighetshanteraren";
$lang["backtogroupmanagement"]="Tillbaka: Grupphanteraren";
$lang["searching_and_access"]="Sökning/åtkomst";
$lang["metadatafields"]="Metadatafält";
$lang["resource_creation_and_management"]="Skapande/hantering av material";
$lang["themes_and_collections"]="Teman/samlingar";
$lang["administration"]="Administration";
$lang["other"]="Övrigt";
$lang["custompermissions"]="Anpassade behörigheter";
$lang["searchcapability"]="Kan söka efter material";
$lang["access_to_restricted_and_confidential_resources"]="Kan se konfidentiella material, kan hämta material med ’begränsad’ åtkomst<br>(normalt endast för administratörer)";
$lang["restrict_access_to_all_available_resources"]="Tillåts åtkomst endast till tillgängliga material";
$lang["can_make_resource_requests"]="Kan begära material";
$lang["show_watermarked_previews_and_thumbnails"]="Ser förhandsgranskningar/miniatyrbilder vattenstämplade";
$lang["can_see_all_fields"]="Kan se alla fält";
$lang["can_see_field"]="Kan se fältet";
$lang["can_edit_all_fields"]="Kan redigera alla fält<br>(för redigeringsbara material)";
$lang["can_edit_field"]="Kan redigera fältet";
$lang["can_see_resource_type"]="Kan se material av typen";
$lang["restricted_access_only_to_resource_type"]="Begränsad åtkomst till material av typen";
$lang["restricted_upload_for_resource_of_type"]="Tillåts inte överföra material av typen";
$lang["edit_access_to_workflow_state"]="Kan redigera material med statusen";
$lang["can_create_resources_and_upload_files-admins"]="Kan skapa material och överföra filer<br>(administratörer; materialen får statusen ’Aktivt’)";
$lang["can_create_resources_and_upload_files-general_users"]="Kan skapa material och överföra filer<br>(vanliga användare; materialen får statusen ’Väntande på granskning’";
$lang["can_delete_resources"]="Kan ta bort material<br>(till vilka användaren har skrivrättighet)";
$lang["can_manage_archive_resources"]="Kan hantera arkivmaterial";
$lang["can_manage_alternative_files"]="Kan hantera alternativa filer";
$lang["can_tag_resources_using_speed_tagging"]="Kan tagga material med Snabbtaggning<br>(måste vara aktiverat i ’config.php’)";
$lang["enable_bottom_collection_bar"]="Aktivera panelen <b>Mina&nbsp;samlingar</b> i nederkant av skärmen";
$lang["can_publish_collections_as_themes"]="Kan publicera samlingar som teman";
$lang["can_see_all_theme_categories"]="Kan se alla temakategorier";
$lang["can_see_theme_category"]="Kan se temakategori";
$lang["can_see_theme_sub_category"]="Kan se underkategori till tema";
$lang["display_only_resources_within_accessible_themes"]="Kan endast söka efter material som hör till teman som användaren har åtkomst till";
$lang["can_access_team_centre"]="Kan nå sidan Administration";
$lang["can_manage_research_requests"]="Kan hantera researchförfrågningar";
$lang["can_manage_resource_requests"]="Kan hantera begäranden av material";
$lang["can_manage_content"]="Kan hantera webbplatsinnehåll";
$lang["can_bulk-mail_users"]="Kan göra massutskick";
$lang["can_manage_users"]="Kan hantera användare";
$lang["can_manage_keywords"]="Kan hantera nyckelord";
$lang["can_access_system_setup"]="Kan nå sidan Systemkonfiguration";
$lang["can_change_own_password"]="Kan ändra lösenordet till det egna användarkontot";
$lang["can_manage_users_in_children_groups"]="Kan hantera användare endast i grupper som är underordnade användarens egen grupp";
$lang["can_email_resources_to_own_and_children_and_parent_groups"]="Kan skicka material per e-post endast till användare i användarens egen grupp och till användare i grupper som är underordnade eller direkt överordnad användarens grupp";

$lang["nodownloadcollection"]="Du har inte behörighet att hämta material från den här samlingen.";

$lang["progress"]="Förlopp";
$lang["ticktodeletethisresearchrequest"]="Om du vill ta bort denna förfrågan markerar du kryssrutan och klickar på <b>Spara</b>";

$lang["done"]="Klar.";

$lang["latlong"]="Latitud, longitud";
$lang["geographicsearch"]="Geografisk sökning";

$lang["geographicsearch_help"]="Klicka och dra när du vill välja ett sökområde.";

$lang["purge"]="Rensa ut";
$lang["purgeuserstitle"]="Rensa ut användare";
$lang["purgeusers"]="Rensa ut användare";
$lang["purgeuserscommand"]="Ta bort användarkonton som inte har varit aktiva de senaste % månaderna, men som skapades före den perioden.";
$lang["purgeusersconfirm"]="Vill du ta bort % användarkonton?";
$lang["pleaseenteravalidnumber"]="Ange ett korrekt nummer";
$lang["purgeusersnousers"]="Det finns inga användare att rensa ut.";

$lang["editallresourcetypewarning"]="Varning! Om du ändrar materialtypen tas eventuell redan lagrad typspecifik metadata bort för materialen.";
$lang["editresourcetypewarning"]="Varning! Om du ändrar materialtypen tas eventuell redan lagrad typspecifik metadata bort för materialet.";

$lang["geodragmode"]="<b>Dragläge</b>";
$lang["geodragmodearea"]="Placera nål";
$lang["geodragmodepan"]="Panorera";

$lang["substituted_original"] = "ersattes av original";
$lang["use_original_if_size"] = "Använd original om vald storlek är otillgänglig";

$lang["originals-available-0"] = "tillgängliga"; # 0 (originals) available
$lang["originals-available-1"] = "tillgängligt"; # 1 (original) available
$lang["originals-available-2"] = "tillgängliga"; # 2+ (originals) available

$lang["inch-short"] = "tum";
$lang["centimetre-short"] = "cm";
$lang["megapixel-short"]="Mpx";
$lang["at-resolution"] = "i"; # E.g. 5.9 in x 4.4 in @ 144 PPI

$lang["deletedresource"] = "Borttaget material";
$lang["deletedresources"] = "Borttagna material";
$lang["nopreviewresources"]= "Material utan förhandsgranskningar";
$lang["action-delete_permanently"] = "Ta bort permanent";

$lang["horizontal"] = "Horisontellt";
$lang["vertical"] = "Vertikalt";

$lang["cc-emailaddress"] = "Kopia till %emailaddress"; # %emailaddress will be replaced, e.g. CC [your email address]

$lang["sort"] = "Sortera";
$lang["sortcollection"] = "Sortera samling";
$lang["emptycollection"] = "Avlägsna materialen";
$lang["deleteresources"] = "Ta bort materialen";
$lang["emptycollectionareyousure"]="Vill du avlägsna alla material från den här samlingen?";

$lang["error-cannoteditemptycollection"]="Du kan inte redigera en tom samling.";
$lang["error-permissiondenied"]="Tillåtelse nekades.";
$lang["error-permissions-login"]="Du måste logga in för att se sidan.";
$lang["error-oldphp"] = "Kräver php-version %version eller senare."; # %version will be replaced with, e.g., "5.2"
$lang["error-collectionnotfound"]="Samlingen hittades inte.";

$lang["header-upload-subtitle"] = "Steg %number: %subtitle"; # %number, %subtitle will be replaced, e.g. Step 1: Specify Default Content For New Resources
$lang["local_upload_path"] = "Lokal överföringsmapp";
$lang["ftp_upload_path"] = "Ftp-mapp";
$lang["foldercontent"] = "Mappinnehåll";
$lang["intro-local_upload"] = "Välj en eller flera filer från den lokala överföringsmappen och klicka sedan på <b>Överför</b>. När filerna är överförda kan de tas bort från överföringsmappen.";
$lang["intro-ftp_upload"] = "Välj en eller flera filer från ftp-mappen och klicka sedan på <b>Överför</b>.";
$lang["intro-java_upload"] = "Klicka på <b>Bläddra</b> för att välja en eller flera filer och klicka sedan på <b>Överför</b>.";
$lang["intro-java_upload-replace_resource"] = "Klicka på <b>Bläddra</b> för att välja en fil och klicka sedan på <b>Överför</b>.";
$lang["intro-single_upload"] = "Klicka på <b>Bläddra</b> för att välja en fil och klicka sedan på <b>Överför</b>.";
$lang["intro-plupload"] = "Klicka på <b>Lägg till filer</b> för att välja en eller flera filer och klicka sedan på <b>Starta överföring</b>.";
$lang["intro-plupload_dragdrop"] = "Dra och släpp eller klicka på <b>Lägg till filer</b> för att välja en eller flera filer och klicka sedan på <b>Starta överföring</b>.";
$lang["intro-plupload_upload-replace_resource"] = "Klicka på <b>Lägg till filer</b> för att välja en fil och klicka sedan på <b>Starta överföring</b>.";
$lang["intro-batch_edit"] = "Ange förvalda inställningar för överföring och förvald metadata för materialen du kommer att överföra.";
$lang["plupload-maxfilesize"] = "Den största tillåtna filstorleken vid överföringar är %s.";
$lang["pluploader_warning"]="Webbläsaren kanske inte stöder överföring av mycket stora filer. Om problem uppstår kan du uppgradera webbläsaren eller använda länkarna nedan.";
$lang["getsilverlight"]="Besök webbplatsen för Microsoft Silverlight om du vill säkerställa att du har den senaste versionen av Silverlight installerad.";
$lang["getbrowserplus"]="Besök webbplatsen för Yahoo BrowserPlus om du vill säkerställa att du har den senaste versionen av BrowserPlus installerad.";
$lang["pluploader_usejava"]="Använd den äldre Java-överföraren.";

$lang["collections-1"] = "(<strong>1</strong> samling)";
$lang["collections-2"] = "(<strong>%number</strong> samlingar)"; # %number will be replaced, e.g. 3 Collections
$lang["total-collections-0"] = "<strong>Totalt: 0</strong> samlingar";
$lang["total-collections-1"] = "<strong>Totalt: 1</strong> samling";
$lang["total-collections-2"] = "<strong>Totalt: %number</strong> samlingar"; # %number will be replaced, e.g. Total: 5 Collections
$lang["owned_by_you-0"] = "(<strong>0</strong> ägda av dig)";
$lang["owned_by_you-1"] = "(<strong>1</strong> ägd av dig)";
$lang["owned_by_you-2"] = "(<strong>%mynumber</strong> ägda av dig)"; # %mynumber will be replaced, e.g. (2 owned by you)

$lang["listresources"]= "Material:";
$lang["action-log"]="Visa logg";
 
$lang["saveuserlist"]="Spara den här listan";
$lang["deleteuserlist"]="Ta bort den här listan";
$lang["typeauserlistname"]="Ange ett användarlistenamn…";
$lang["loadasaveduserlist"]="Läs in en sparad användarlista";
 
$lang["searchbypage"]="Sök sida";
$lang["searchbyname"]="Sök namn";
$lang["searchbytext"]="Sök text";
$lang["saveandreturntolist"]="Spara och återvänd till lista";
$lang["backtomanagecontent"]="Tillbaka: Hantera webbplatsens innehåll";
$lang["editcontent"]="Redigera innehåll";
 
$lang["confirmcollectiondownload"]="Vänta medan arkivet skapas. Detta kan ta en stund och tiden är beroende av den totala storleken av de ingående materialen.";
$lang["collectiondownloadinprogress"]='Vänta medan arkivet skapas. Detta kan ta en stund och tiden är beroende av den totala storleken av de ingående materialen.<br /><br />Om du vill fortsätta arbeta kan du <a href=\"home.php\" target=\"_blank\">&gt; Öppna ett nytt webbläsarfönster</a><br /><br />';
$lang["preparingzip"]="Förbereder …";
$lang["filesaddedtozip"]="filer kopierade";
$lang["fileaddedtozip"]="fil kopierad";
$lang["zipping"]="Zippar";
$lang["zipcomplete"]="Filhämtningen bör ha påbörjats. Du kan lämna den här sidan.";

$lang["starttypingkeyword"]="Ange nyckelord…";
$lang["createnewentryfor"]="Skapa nytt nyckelord: ";
$lang["confirmcreatenewentryfor"]="Vill du skapa en ny post i nyckelordslistan för ’%%’?";
 
$lang["editresourcepreviews"]="Redigera materialens förhandsgranskningar";
$lang["can_assign_resource_requests"]="Kan tilldela andra användare begäranden av material";
$lang["can_be_assigned_resource_requests"]="Kan bli tilldelad begäranden av material (kan även se tilldelade begäranden på sidan Hantera begäranden/beställningar)";
 
$lang["declinereason"]="Skäl för avslag";
$lang["approvalreason"]="Skäl för bifall";

$lang["requestnotassignedtoyou"]="Denna begäran är inte längre tilldelad dig. Den är nu tilldelad användare %.";
$lang["requestassignedtoyou"]="Materialbegäran tilldelad dig";
$lang["requestassignedtoyoumail"]="En materialbegäran har tilldelats dig. Klicka på länken nedan om du vill bifalla eller avslå den.";
 
$lang["manageresources-overquota"]="Materialhantering inaktiverad – du har överskridit din diskutrymmestilldelning";
$lang["searchitemsdiskusage"]="Diskutrymme som används av resultatet";
$lang["matchingresourceslabel"]="Matchande material";
 
$lang["saving"]="Sparar …";
$lang["saved"]="Sparat";
 
$lang["resourceids"]="Materialnr";
 
$lang["warningrequestapprovalfield"]="Varning! Beträffande materialnr % – notera följande innan ett eventuellt bifallande!";

$lang["yyyy-mm-dd"]="ÅÅÅÅ-MM-DD";

$lang["resources-with-requeststatus0-0"]="(ingen väntande)"; # 0 Pending
$lang["resources-with-requeststatus0-1"]="(1 väntande)"; # 1 Pending
$lang["resources-with-requeststatus0-2"]="(%number väntande)"; # %number will be replaced, e.g. 3 Pending
$lang["researches-with-requeststatus0-0"]="(alla tilldelade)"; # 0 Unassigned
$lang["researches-with-requeststatus0-1"]="(1 ej tilldelad)"; # 1 Unassigned
$lang["researches-with-requeststatus0-2"]="(%number ej tilldelade)"; # %number will be replaced, e.g. 3 Unassigned

$lang["byte-symbol"]="B";
$lang["kilobyte-symbol"]="kB"; # Egentligen handlar det om kiB o.s.v., men jag följer RS beteckningar.
$lang["megabyte-symbol"]="MB";
$lang["gigabyte-symbol"]="GB";
$lang["terabyte-symbol"]="TB";

$lang["upload_files"]="Överför filer";
$lang["upload_files-to_collection"]="Överför filer (till samlingen ’%collection’)"; # %collection will be replaced, e.g. Upload Files (to the collection 'My Collection')

$lang["ascending"] = "Stigande";
$lang["descending"] = "Fallande";
$lang["sort-type"] = "Sorteringsriktning";
$lang["collection-order"] = "Ordning i samling";
$lang["save-error"]="Varning! Ändringarna kunde inte sparas automatiskt – spara ändringarna manuellt!";

$lang["theme_home_promote"]="Puffa för på startsidan?";
$lang["theme_home_page_text"]="Pufftext";
$lang["theme_home_page_image"]="Puffbild";
$lang["ref-title"] = "%ref – %title"; # %ref and %title will be replaced, e.g. 3 - Sunset

$lang["error-pageload"] = "Sidan kunde inte läsas in. Om du utförde en sökning kan du prova att förfina sökfrågan. Kontakta systemadministratören om problemet är bestående.";

$lang["copy-field"]="Kopiera fält";
$lang["copy-to-resource-type"]="Kopiera till materialtyp";
$lang["synchronise-changes-with-this-field"]="Synkronisera ändringar med detta fält";
$lang["copy-completed"]="Kopieringen slutförd. Det nya fältet har nr ?.";

$lang["nothing-to-display"]="Inget att visa.";
$lang["report-send-all-users"]="Skicka rapporten till alla aktiva användare?";

$lang["contactsheet-single"]="1 per sida";
$lang["contact_sheet-include_header_option"]="Visa rubrik?";
$lang["contact_sheet-add_link_option"]="Lägg till klickbara länkar till sidorna som visar materialen?";
$lang["contact_sheet-add_logo_option"]="Lägg till logotyp i sidhuvudet?";
$lang["contact_sheet-single_select_size"]="Bildkvalitet";

$lang["caps-lock-on"]="Varning! Versallåset är aktiverat.";
$lang["collectionnames"]="Samlingsnamn";
$lang["findcollectionthemes"]="Teman";
$lang["upload-options"]="Överföringsinställningar";
$lang["user-preferences"]="Användarinställningar";
$lang["allresources"]="Alla material";

$lang["smart_collection_result_limit"]="Smart samling – begränsning av antalet resultat";

$lang["untaggedresources"]="Material utan data i fältet ’%field’";

$lang["secureyouradminaccount"]="Välkommen! Du måste nu byta det förinställda lösenordet för att säkra servern.";
$lang["resources-all-types"]="Alla materialtyper";
$lang["search-mode"]="Sök efter …";
$lang["action-viewmatchingresults"]="Visa matchande resultat";
$lang["nomatchingresults"]="Inga matchande resultat";
$lang["matchingresults"]="matchande resultat"; # e.g. 17 matching results=======
$lang["resources"]="Material";
$lang["share-resource"]="Dela material";
$lang["scope"]="Omfång";
$lang["downloadmetadata"]="Hämta metadata";
$lang["downloadingmetadata"]="Hämta metadata";
$lang["file-contains-metadata"]="Klicka på länken nedan om du vill hämta en textfil innehållande materialets metadata.";
$lang["metadata"]="Metadata";
$lang["textfile"]="Textfil";

# Comments field titles, prompts and default placeholders
$lang['comments_box-title']="Kommentarer";
$lang['comments_box-policy']="Kommentarspolicy";
$lang['comments_box-policy-placeholder']="Lägg till en text för sidan comments_policy i webbplatsens innehåll.";		# only shown if Admin User and no policy set
$lang['comments_in-response-to']="som svar på";
$lang['comments_respond-to-this-comment']="Svara";
$lang['comments_in-response-to-on']="";
$lang['comments_anonymous-user']="Anonym";
$lang['comments_submit-button-label']="Skicka";
$lang['comments_body-placeholder']="Lägg till en kommentar";
$lang['comments_fullname-placeholder']="Ditt namn (obligatoriskt)";
$lang['comments_email-placeholder']="Din e-postadress (obligatorisk)";
$lang['comments_website-url-placeholder']="Webbplats";
$lang['comments_flag-this-comment']="Flagga denna kommentar";
$lang['comments_flag-has-been-flagged']="Denna kommentar har blivit flaggad";
$lang['comments_flag-reason-placeholder']="Skäl till att flagga denna kommentar";
$lang['comments_validation-fields-failed']="Du måste fylla i alla obligatoriska fält!";
#$lang['comments_block_comment_label']="block comment";
$lang['comments_flag-email-default-subject']="Avisering av flaggad kommentar";
$lang['comments_flag-email-default-body']="Den här kommentaren har blivit flaggad:";
$lang['comments_flag-email-flagged-by']="Flaggad av:";
$lang['comments_flag-email-flagged-reason']="Skäl för flaggning:";
$lang['comments_hide-comment-text-link']="Ta bort kommentar";
$lang['comments_hide-comment-text-confirm']="Vill du ta bort texten för denna kommentar?";

# testing updated request emails
$lang["request_id"]="Begäransnr:";
$lang["user_made_request"]="Följande användare har gjort en begäran:";

$lang["download_collection"]="Hämta samling";

$lang["all-resourcetypes"] = "material"; # Will be used as %resourcetypes% if all resourcetypes are searched.
$lang["all-collectiontypes"] = "samlingar"; # Will be used as %collectiontypes% if all collection types are searched.
$lang["resourcetypes-no_collections"] = "Alla %resourcetypes%"; # Use %RESOURCETYPES%, %resourcetypes% or %Resourcetypes% as a placeholder. The placeholder will be replaced with the resourcetype in plural (or $lang["all-resourcetypes"]), using the same case. E.g. "All %resourcetypes%" -> "All photos"
$lang["no_resourcetypes-collections"] = "Alla %collectiontypes%"; # Use %COLLECTIONTYPES%, %collectiontypes% or %Collectiontypes% as a placeholder. The placeholder will be replaced with the collectiontype (or $lang["all-collectiontypes"]), using the same case. E.g. "All %collectiontypes%" -> "All my collections"
$lang["resourcetypes-collections"] = "Alla %resourcetypes% och alla %collectiontypes%"; # Please find the comments for $lang["resourcetypes-no_collections"] and $lang["no_resourcetypes-collections"]!
$lang["resourcetypes_separator"] = ", "; # The separator to be used when converting the array of searched resourcetype to a string. E.g. ", " -> "photos, documents"
$lang["collectiontypes_separator"] = ", "; # The separator to be used when converting the array of searched collections to a string. E.g. ", " -> "public collections, themes"
$lang["hide_view_access_to_workflow_state"]="Blockera åtkomst till status";
$lang["collection_share_status_warning"]="Varning! Denna samling har material med följande status, kontrollera att dessa material kommer att vara tillgängliga för andra användare";
$lang["contactadmin"]="Kontakta administratör";
$lang["contactadminintro"]="Skriv ett meddelande och klicka på <b>Skicka</b>.";
$lang["contactadminemailtext"]=" har skickat dig ett e-postmeddelande om ett material";
$lang["showgeolocationpanel"]="Visa platsinformation";
$lang["hidegeolocationpanel"]="Dölj platsinformation";
$lang["download_usage_option_blocked"]="Detta användningsalternativ är inte tillgängligt. Kontakta vid behov systemets administratör.";

$lang["tagcloudtext"]="Med vilken metadata har materialen taggats? Ju oftare en tagg har använts desto större storlek har den i molnet.<br /><br />Klicka på valfri tagg om du vill göra en sökning.";
$lang["tagcloud"]="Taggmoln";



$lang["about__about"]="Din egen text för ’Om oss’ …";
$lang["all__footer"]="<a target=\"_blank\" href=\"http://www.resourcespace.org/\">ResourceSpace</a>: Digital materialförvaltning (dam) med öppen källkod";
$lang["all__researchrequest"]="Låt vårt team hitta materialen du är ute efter.";
$lang["all__searchpanel"]="Sök efter material genom att ange beskrivning, nyckelord eller materialnr.";
$lang["change_language__introtext"]="Välj ditt önskade språk nedan.";
$lang["change_password__introtext"]="Skriv in ett nytt lösenord nedan om du vill byta lösenord.";
$lang["collection_edit__introtext"]="Organisera och hantera dina material genom att dela upp dem i samlingar.\n\n<br />\n\nDu når alla dina samlingar från panelen <b>Mina&nbsp;samlingar</b> i nederkant av skärmen.\n\n<br /><br />\n\n<strong>Privat åtkomst</strong> tillåter endast dig och dina utvalda användare att se samlingen. Idealiskt om du vill samla material i projekt som du jobbar med enskilt eller i en grupp.\n\n<br /><br />\n\n<strong>Gemensam åtkomst</strong> tillåter alla interna användare att söka efter och se samlingen. Användbart om du vill dela materialet med andra som skulle kunna ha nytta av det. \n\n<br /><br />\n\nDu kan välja om du vill att de andra användarna ska kunna lägga till och ta bort material eller bara kunna visa materialen.";
$lang["collection_email__introtext"]="Dela snabbt och enkelt materialet i denna samling med andra. Ett e-postmeddelande innehållande en webblänk till samlingarna skapas och skickas automatiskt. Du kan även lägga till ett eget meddelande.";
$lang["collection_email__introtextthemeshare"]="Dela snabbt och enkelt alla teman i denna temakategori med andra. Ett e-postmeddelande innehållande en webblänk till respektive tema skapas och skickas automatiskt. Du kan även lägga till ett eget meddelande.";
$lang["collection_manage__findpublic"]="Gemensamma samlingar är grupper av material som har gjorts allmänt tillgängliga av användare i systemet. Skriv in ett samlingsnr, hela eller delar av samlingsnamnet, eller ett användarnamn när du vill söka efter en gemensam samling. Lägg till den hittade samlingen till din lista över samlingar om du vill kunna nå materialen enkelt.";
$lang["collection_manage__introtext"]="Organisera och hantera ditt material genom att dela upp det i samlingar. Skapa samlingar för ett eget projekt, om du vill underlätta samarbetet i en projektgrupp eller om du vill samla dina favoriter på ett ställe. Du når alla dina samlingar från panelen <b>Mina&nbsp;samlingar</b> i nederkant av skärmen.";
$lang["collection_manage__newcollection"]="Fyll i ett samlingsnamn om du vill skapa en ny samling.";
$lang["collection_public__introtext"]="Gemensamma samlingar är skapade av andra användare.";
$lang["contact__contact"]="Dina kontaktuppgifter …";
$lang["contribute__introtext"]="Du kan bidra med eget material. När du först skapar materialet får det statusen ’Under registrering’. Överför en eller flera filer och fyll i fälten med relevant metadata. Sätt statusen till ’Väntande på granskning’ när du är klar.";
$lang["delete__introtext"]="Du måste ange ditt lösenord för att bekräfta att du vill ta bort det här materialet.";
$lang["done__collection_email"]="Ett e-postmeddelande innehållande en webblänk har skickats till användarna du specificerade. Samlingen har lagts till i respektive användares panel <b>Mina&nbsp;samlingar</b>.";
$lang["done__deleted"]="Materialet har tagits bort.";
$lang["done__research_request"]="En medlem av researchteamet kommer att få i uppdrag att besvara din researchförfrågan. Vi kommer att hålla kontakt genom e-post under arbetets gång. När vi har slutfört arbetet kommer du att få ett e-postmeddelande med en webblänk till alla material vi rekommenderar.";
$lang["done__resource_email"]="Ett e-postmeddelande innehållande en webblänk till materialen har skickats till användarna du specificerade.";
$lang["done__resource_request"]="Din begäran har mottagits och vi kommer att höra av oss inom kort.";
$lang["done__user_password"]="Ett e-postmeddelande innehållande ditt användarnamn och lösenord har skickats.";
$lang["done__user_request"]="Din ansökan om ett användarkonto har skickats. Dina inloggningsuppgifter kommer att skickas till dig inom kort.";
$lang["download_click__introtext"]="Högerklicka på länken nedan och välj <b>Spara&nbsp;som</b> om du vill hämta materialet. Du kommer att få frågan var du vill spara filen. Öppna filen i din webbläsare genom att klicka på webblänken.";
$lang["download_progress__introtext"]="Din hämtning startas inom kort. När hämtningen är klar kan du fortsätta genom att klicka på länkarna nedan.";
$lang["edit__batch"]="";
$lang["edit__multiple"]="Markera de fält du vill uppdatera med ny information. Omarkerade fält lämnas oförändrade.";
$lang["help__introtext"]="Få ut det mesta möjliga av ResourceSpace. Instruktionerna nedan hjälper dig att använda systemet och materialen effektivare.</p>\n\n<p>Använd Teman om du vill bläddra bland material per tema eller använd Enkel sökning om du vill söka efter specifikt material.</p>\n\n<p><a target=\"_blank\" href=\"http://www.montala.net/downloads/resourcespace-GettingStarted.pdf\">Hämta den engelska användarhandboken (pdf-fil)</a>\n\n<p><a target=\"_blank\" href=\"http://wiki.resourcespace.org/index.php/?title=main_Page\">Dokumentation på webben (engelskspråkig wiki)</a>";
$lang["home__help"]="Hjälp och tips som ser till att du får ut det mesta möjliga av ResourceSpace.";
$lang["home__mycollections"]="Organisera dina material och samarbeta med andra. De här verktygen hjälper dig att arbeta mer effektivt.";
$lang["home__restrictedtext"]="Klicka på webblänken som skickades till dig om du vill komma åt materialen som är utvalda för dig.";
$lang["home__restrictedtitle"]="<h1>Välkommen till ResourceSpace</h1>";
$lang["home__themes"]="De bästa materialen, speciellt utvalda och sorterade.";
$lang["home__welcometext"]="Skriv en introduktion här …";
$lang["home__welcometitle"]="Välkommen till ResourceSpace";
$lang["login__welcomelogin"]="Välkommen till ResourceSpace";
$lang["research_request__introtext"]="Researchteamet hjälper dig att finna de bästa materialen till dina projekt. Fyll i formuläret nedan så noggrant som möjligt så att vi kan ge dig ett relevant svar. <br /><br />En medlem av teamet kommer att få i uppdrag att besvara din researchförfrågan. Vi kommer att hålla kontakt genom e-post under arbetets gång. När vi har slutfört arbetet kommer du att få ett e-postmeddelande med en webblänk till alla material vi rekommenderar.";
$lang["resource_email__introtext"]="Dela snabbt och enkelt material med andra. Ett e-postmeddelande innehållande en webblänk till materialen skapas och skickas automatiskt. Du kan även lägga till ett eget meddelande.";
$lang["resource_request__introtext"]="Din begäran är nästan slutförd. Ange anledningen till din begäran så att vi kan besvara den snabbt och effektivt.";
$lang["search_advanced__introtext"]="<strong>Söktips</strong><br />Ett avsnitt som du lämnar tomt eller omarkerat medför att <i>allt</i> inkluderas i sökningen. Om du till exempel lämnar alla länders kryssrutor omarkerade, begränsas sökningen inte med avseende på land. Om du däremot sedan markerar kryssrutan ’Sverige’ ger sökningen endast material från just Sverige.";
$lang["tag__introtext"]="Hjälp till att förbättra framtida sökresultat genom att förse materialen med relevant metadata. Ange till exempel nyckelord som beskrivning av vad du ser på en bild: kanin, hus, boll, födelsedagstårta. Separera nyckelorden med kommatecken eller mellanslag. Ange fullständiga namn på alla personer som förekommer på ett fotografi. Ange platsen för ett fotografi om den är känd.";
$lang["team_archive__introtext"]="Om du vill redigera ett arkiverat material gör du det enklast genom att söka efter det här och sedan klicka på <b>Redigera</b> på sidan som visar materialet. Alla material som väntar på arkivering kan enkelt nås från länken nedan. Lägg till eventuell relevant information innan du flyttar materialet till arkivet.";
$lang["team_batch__introtext"]="";
$lang["team_batch_select__introtext"]="";
$lang["team_batch_upload__introtext"]="";
$lang["team_copy__introtext"]="Ange numret för materialet du vill kopiera. Endast materialets metadata kommer att kopieras – eventuella filer kommer inte att kopieras.";
$lang["team_home__introtext"]="Välkommen till sidan Administration. Använd länkarna nedan om du vill administrera material, svara på förfrågningar, hantera teman och ändra systeminställningar.";
$lang["team_report__introtext"]="Välj en rapport och en period. Rapporten kan öppnas i till exempel MS Excel eller LibreOffice Calc.";
$lang["team_research__introtext"]="Organisera och hantera researchförfrågningar.<br /><br />Välj verktyget <b>Redigera researchförfrågan</b> om du vill granska förfrågan och tilldela en medlem i researchteamet uppdraget att besvara förfrågan. Med samma verktyg kan du lägga till en befintlig samling till researchen genom att ange samlingens nummer.<br /><br />När en medlem har tilldelats researchen blir den tillgänglig för medlemmen i panelen <b>Mina&nbsp;samlingar</b>. Använd de vanliga verktygen om du vill lägga till material till researchen.<br /><br />När researchen är slutförd väljer du återigen verktyget <b>Redigera researchförfrågan</b> och ändrar status till ’Besvarad’. När du klickar på <b>Spara</b> skickas automatiskt ett e-postmeddelande till användaren som skickade researchförfrågan. Meddelandet innehåller en webblänk som leder till researchen och den läggs också till i användarens panel<b>Mina&nbsp;samlingar</b>.";
$lang["team_resource__introtext"]="Lägg till material ett och ett eller i grupp. Om du vill redigera ett material kan du enklast söka efter det och sedan klicka på <b>Redigera</b> på sidan som visar materialet.";
$lang["team_stats__introtext"]="En statistikrapport kan skapas vid behov, baserad på aktuell data. Markera kryssrutan om du vill skriva ut all statistik för det valda året.";
$lang["team_user__introtext"]="Använd den här delen om du vill lägga till, ta bort eller redigera användare.";
$lang["terms__introtext"]="Innan du kan fortsätta måste du acceptera reglerna och villkoren.";
$lang["terms__terms"]="Dina regler …";
$lang["terms and conditions__terms and conditions"]="Dina regler och villkor …";
$lang["themes__findpublic"]="Gemensamma samlingar är samlingar med material som har delats ut av andra användare.";
$lang["themes__introtext"]="Teman är grupper av material som har valts ut av administratörerna som exempel på vilka material som finns i systemet.";
$lang["themes__manage"]="Organisera och redigera tillgängliga teman. Teman är grupper av material som har valts ut av administratörerna som exempel på vilka material som finns i systemet.<br /><br /><strong>Skapa teman</strong><br /><Om du vill skapa ett nytt tema måste du först skapa en samling.<br />Gå till <b>Mina&nbsp;samlingar</b> och skapa en ny <strong>gemensam samling</strong>. Välj en temakategori från listan om du vill lägga till samlingen i en existerande temakategori eller ange ett nytt namn om du vill skapa en ny temakategori. Tillåt inte användare att lägga till/ta bort material från teman.<br /><br /><strong>Redigera teman</strong><br />Om du vill redigera materialen i ett existerande tema väljer du verktyget <strong>Välj samling</strong>. Materialen i samlingen blir då åtkomliga i panelen <b>Mina&nbsp;samlingar</b> i nederkanten av skärmen. Använd de vanliga verktygen om du vill redigera, lägga till eller ta bort material.<br /><br /><strong>Byta namn på teman och flytta samlingar</strong><br />Välj verktyget <strong>Redigera samling</strong>. Ange ett nytt namn i fältet Namn om du vill byta namn på temat. Välj en temakategori från listan om du vill flytta samlingen till en existerande temakategori eller ange ett nytt namn om du vill skapa en ny temakategori och flytta samlingen dit.<br /><br /><strong>Ta bort en samling från ett tema</strong><br />Välj verktyget <strong>Redigera samling</strong> och rensa fältet Temakategori och fältet där nya temakategorinamn anges.";
$lang["upload__introtext"]="";
$lang["upload_swf__introtext"]="";
$lang["user_password__introtext"]="Fyll i din e-postadress och ditt användarnamn så kommer ett nytt lösenord att skickas till dig.";
$lang["user_request__introtext"]="Fyll i formuläret nedan om du vill ansöka om ett användarkonto.";
$lang["view__storyextract"]="Textutdrag:";
