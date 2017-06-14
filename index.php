<?php
  
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   //-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
   /**
    * Recursive function to check whether a particular file is present.
    *
    * @param inFolder the base folder to search for files.
    * @param inPattern the filter for selecting files.
    * @retval true if found.
    */
 
	  function getFileList( $inFolder, $depth, $inPattern )
	  {
	      // Get list of folders
          // echo "getFileList( \"{$inFolder}\" )\n";
	      $folderList = scandir( $inFolder );
	      $fileArray = array();

		  if ( isBlockingFilePresent( $inFolder, $inPattern ) ) {
             // HTML file in this folder so scan this folder for HTML files and exit
             // Scan round list of files and insert into array keyed by name (not time code).
             foreach ( $folderList as $key => $file ) {
                if ( !isHiddenFile( $file ) ) {
            		$fullName = "{$inFolder}/{$file}";
                    // $lastModified = date( "F d Y, H:i", filemtime( $fullName ) );
                    $fileArray[ $file ] = filemtime( $fullName );
             	}
             }
    // Now output contents of array with HTML tags
         // First sort on value (modified time)
         arsort( $fileArray );
         foreach ( $fileArray as $file => $timeStamp ) {
             $formatedTimeStamp = date( "F d Y, H:i", $timeStamp );
            echo str_repeat( " ", $depth * 3 ) . "{$file} : {$formatedTimeStamp}\n";
         }
         } else {
	         // No html files so scan this folder and recurse to next level if folder found
			 foreach ( $folderList as $key => $folder ) {
                if ( !isHiddenFile( $folder ) ) {
            		$fullName = "{$inFolder}{$folder}";
                    echo "{$fullName}\"\n";
					if ( is_dir( $fullName ) ) {
					    // Get the time stamp based on youngest contained file. This reflects
					    // the youngest time stamp of the contained folders/files
				        $containedTimeStamp = getYoungestFolderTimeCode( $fullName, $inPattern );
				        // Use this in the array of folders
                        $fileArray[ $folder ] = $containedTimeStamp;
						// Now we can recurse into this folder
						getFileList( $fullName, $depth + 1, $inPattern );
					}
                }
			 }
			          // Now output contents of array with HTML tags
         // First sort on value (modified time)
         arsort( $fileArray );
         foreach ( $fileArray as $file => $timeStamp ) {
             $formatedTimeStamp = date( "F d Y, H:i", $timeStamp );
            echo str_repeat( " ", $depth * 3 ) . "{$file} : {$formatedTimeStamp}\n";
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
      return preg_match( "/^\./", $inFile );
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
 
   function isBlockingFilePresent( $inFolder, $inPattern )
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
      // echo "getYoungestFolderTimeCode( \"{$inFolder}\" )\n";
      $dirListing = scandir( $inFolder );
      // fileListing is now a list of files/folders. We need to check for
      // HTML files stop any further processing
      if ( isBlockingFilePresent( $inFolder, $inPattern ) ) {
         // Stop processing here but scan html files for the most recent.
         return getYoungestFileTimeCode( $inFolder, $inPattern );
      } else {
         // Scan list of folders contained here (if any)
         foreach ( $dirListing as $key => $folder ) {
            // Ignore hidden files/folders
            if ( !isHiddenFile( $folder ) ) {
               $fullName = "{$inFolder}{$folder}";
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
               // echo "   found file [{$file}] - " . date( "F d Y, H:i", $thisTimeCode ) . "\n";
            }
         }
      }
      return $maxTimeCode;
   }

    date_default_timezone_set('Europe/London');
    $youngestFileTime = getYoungestFolderTimeCode( "project/", "/\.html$/" );
    // echo " Youngest file - " . date( "F d Y, H:i", $youngestFileTime ) . "\n\n\n";
    
    echo getFileList( "project/", 0, "/\.html$/" );
           
?>