	<?php
  
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to display (HTML) a list of files with most recent
    * at the top.
    *
    * @param inFolder the base folder to display folders/files.
    * @param inPattern the filter for selecting files (defaults to html files).
    * @param inDepth the depth of search (only used internally).
    */
 
	  function displayRecentFilesList( $inFolder, $inPattern = "/\.html$/", $inDepth = 1 )
	  {
	      // Get list of folders
	      $folderList = scandir( $inFolder );
	      $fileArray = array();

		  if ( isMatchingFileInFolder( $inFolder, $inPattern ) ) {
             // HTML file in this folder so scan this folder for HTML files and exit
             // Scan round list of files and insert into array keyed by name (not time code).
			 echo "<div class=\"list\" data-level=\"{$inDepth}\">\n";
             foreach ( $folderList as $key => $file ) {
                if ( !isHiddenFile( $file ) ) {
            		$fullName = "{$inFolder}/{$file}";
                    // $lastModified = date( "F d Y, H:i", filemtime( $fullName ) );
                    if ( !is_dir( $fullName ) ) {
	                    $fileArray[ $file ] = filemtime( $fullName );
	                }
             	}
             }
             arsort( $fileArray );
             foreach ( $fileArray as $file => $timeStamp ) {
                 $fullName = "{$inFolder}/{$file}";
                 $formatedTimeStamp = date( "F d Y, H:i", $timeStamp );
                 echo "   <div class=\"item\"><a href=\"{$fullName}\" target=\"_blank\">{$file}</a> <span class=\"date\">{$formatedTimeStamp}</span> </div>\n";
             }
	         echo "</div>\n";
             return;
         } else {
	         // No html files so scan this folder and recurse to next level if folder found
             // Produce an array of the folders to traverse with their time stamps
			 foreach ( $folderList as $key => $folder ) {
                if ( !isHiddenFile( $folder ) ) {
                    $fullName = "{$inFolder}/{$folder}";
    		        if ( is_dir( $fullName ) ) {
	    		       
    				   // Get the time stamp based on youngest contained file. This reflects
    				   // the youngest time stamp of the contained folders/files
    				   $containedTimeStamp = getYoungestFolderTimeCode( $fullName, $inPattern );
    				   // Use this in the array of folders keyed by file name
                       $fileArray[ $folder ] = $containedTimeStamp;
    		        }
                }
			 }
			 // At this point we have an array of folders with their time stamps so sort
             arsort( $fileArray );
             // Now traverse this recursing if necessary
			 foreach ( $fileArray as $folder => $timeStamp ) {
        		$fullName = "{$inFolder}/{$folder}";
                $formatedTimeStamp = date( "F d Y, H:i", $timeStamp );
                echo "<div class=\"folder\" data-folder=\"{$inDepth}\" data-time=\"{$timeStamp}\">\n";
                echo "<h{$inDepth} class=\"folderTitle\">{$folder}</h{$inDepth}>\n";
				displayRecentFilesList( $fullName, $inPattern, $inDepth + 1 );
		        echo "</div>\n";	
			 }

          }
      }
      
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to display (HTML) a list of files in alpha order.
    *
    * @param inFolder the base folder to display folders/files.
    * @param inPattern the filter for selecting files (defaults to html files).
    * @param inDepth the depth of search (only used internally).
    */

 	  function displayAlphaFilesList( $inFolder, $inPattern = "/\.html$/", $depth = 1 )
	  {
	    $dirListing = scandir( $inFolder );
	
		if ( isMatchingFileInFolder( $inFolder, $inPattern ) ) {
			// HTML file in this folder so scan this folder for HTML files and exit
			echo "<div class=\"list\" data-level=\"{$depth}\">";
	       	foreach ( $dirListing as $key => $file ) {
		       	// If file name ends with html then output file name
                if ( !isHiddenFile( $file ) ) {
					$fullName = "{$inFolder}/{$file}";
				    if ( preg_match( $inPattern, $file ) ) {
		 		      // echo file name
		 		      $lastModified = date( "F d Y, H:i", filemtime( $fullName ) );
		 			  echo "<div class=\"item\"><a href=\"{$fullName}\" target=\"_blank\">{$file}</a> <span class=\"date\">{$lastModified}</span> </div>\n";
		 			}
	 			}
			}
			echo "</div>";
			return;
		} else {
			// No matching files so scan this folder and recurse to next level if folder found
			foreach ( $dirListing as $key => $folder ) {
		        if ( !isHiddenFile( $folder ) ) {
					$fullName = "{$inFolder}/{$folder}";
			        echo "<div class=\"folder\" data-folder=\"{$depth}\">";
					if ( is_dir( $fullName ) ) {
						// output a new heading at this level
						echo "<h{$depth} class=\"folderTitle\">{$folder}</h{$depth}>\n";
						// Recurse into this folder
						displayAlphaFilesList( $fullName, $inPattern, $depth + 1 );
					}
					echo "</div>";	
				}
			}
		}
	  }


   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to check whether a particular file is present.
    *
    * @param inFolder the base folder to search for files.
    * @param inPattern the filter for selecting files.
    * @retval true if found.
    */
 
   function isHiddenFile( $inFile )
   {
      return preg_match( "/^(\.)|(_)/", $inFile );
   }

   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to check whether a particular file is present.
    *
    * @param inFolder the base folder to search for files.
    * @param inPattern the filter for selecting files.
    * @retval true if found.
    */
 
   function isMatchingFileInFolder( $inFolder, $inPattern )
   {
       $fileListing = scandir( $inFolder );
       foreach ( $fileListing as $key => $value ) {
         if ( preg_match( $inPattern, $value ) ) {
            return true;
        }
      }
      return false;
   }

   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to find the most recent folder. It only returns the 
    * the most recent time code (the highest value). It searches appropriate
    * files within a folder.
    *
    * @param inFolder the base folder to search for folders.
    * @param inPattern the filter for selecting files.
    * @retval the time code of the most recent folder.
    */
 
   function getYoungestFolderTimeCode( $inFolder, $inPattern )
   {
      $maxTimeCode = 0;
      $dirListing = scandir( $inFolder );
      // fileListing is now a list of files/folders. We need to check for
      // HTML files stop any further processing
      if ( isMatchingFileInFolder( $inFolder, $inPattern ) ) {
         // Stop processing here but scan html files for the most recent.
         return getYoungestFileTimeCode( $inFolder, $inPattern );
      } else {
         // Scan list of folders contained here (if any)
         foreach ( $dirListing as $key => $folder ) {
            // Ignore hidden files/folders
            if ( !isHiddenFile( $folder ) ) {
               $fullName = "{$inFolder}/{$folder}";
               if ( is_dir( $fullName ) ) {
                  // Get the time code of this folder without contents
                  $thisTimeCode = filemtime( $fullName );
                  // echo "   found folder [{$folder}] - " . date( "F d Y, H:i", $thisTimeCode ) . "\n";
                  $contentsTimeCode = getYoungestFolderTimeCode( $fullName, $inPattern );
                  if ( $contentsTimeCode > $thisTimeCode ) {
                     $thisTimeCode = $contentsTimeCode;
                  }
                  if ( $thisTimeCode > $maxTimeCode ) {
                     $maxTimeCode = $thisTimeCode;
                  }
               }
            }
         }
      }
      return $maxTimeCode;
   }
       
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Function to find the most recent file matching a pattern. It only
    * returns the the most recent time code (the highest value).
    *
    * @param inFolder the base folder to search for folders.
    * @param inPattern the filter for selecting files.
    * @retval the time code of the most recent file.
    */
 
   function getYoungestFileTimeCode( $inFolder, $inPattern )
   {
      $maxTimeCode = 0;
      // echo "scanning for files \"{$inFolder}\"\n";
      $fileListing = scandir( $inFolder );
      foreach ( $fileListing as $key => $file ) {
        if ( !isHiddenFile( $file ) ) {
           $fullName = "{$inFolder}/{$file}";
           $thisTimeCode = filemtime( $fullName );
           if ( !is_dir( $fullName ) ) {
               if ( $thisTimeCode > $maxTimeCode ) {
                  $maxTimeCode = $thisTimeCode;
               }
            }
         }
      }
      return $maxTimeCode;
   }

    date_default_timezone_set('Europe/London');
    // displayRecentFilesList( "projects/" );
    displayAlphaFilesList( "projects/" );
           
?>
	
