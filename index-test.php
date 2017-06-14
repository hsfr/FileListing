<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
	<title>Devious Ideas</title>
	<link rel='stylesheet' href='fonts/brand/brandicon.css'>
	<script src='js/jquery.min.js'></script>
	<script type="text/javascript">
	    $(document).ready(function() { 
		    
		    //on load hide the lists
		    $('.list').slideToggle(200);
		    // on click toggle lists
		    $('.folder .folderTitle').on('click', function(e) {
			   e.preventDefault();
			   //e.stopPropagation();
			   
			   var link = $(this);
			   
			   $(this).next('.list').slideToggle(200);
			    
		    });
		    
		    
		    
		    });
    </script>
</head>
<body>
	
	
	<style>
		@font-face {
		  font-family: 'Calibre-Thin';
		  src: url(fonts/calibre/Calibre-Thin.otf);
		  font-weight: normal;
		  font-style: normal; }
		@font-face {
		  font-family: 'Calibre-Light';
		  src: url(fonts/calibre/Calibre-Light.otf);
		  font-weight: normal;
		  font-style: normal; }
		@font-face {
		  font-family: 'Calibre-Regular';
		  src: url(fonts/calibre/Calibre-Regular.otf);
		  font-weight: normal;
		  font-style: normal; }
		@font-face {
		  font-family: "Calibre-Semibold";
		  src: url(fonts/calibre/Calibre-Semibold.otf) format("opentype");
		  font-weight: normal;
		  font-style: normal; }
		@font-face {
		  font-family: "Calibre-Bold";
		  src: url(fonts/calibre/Calibre-Bold.otf) format("opentype");
		  font-weight: normal;
		  font-style: normal; }
  
		* {
			box-sizing: border-box;
		}
		
		body { 
			font-family: 'Calibre-Regular';
			font-size: 13px;
			color: #666;
			background: #f2f2f2;
			padding: 50px 25px;
			min-width: 640px;
		}
		
		section {
			width: 100%;
			max-width: 720px;
			min-width: 640px;
			margin: 0 auto;
			display: block;
		}
		
		h1, h2, h3, h4 {
			font-size: 15px;
			margin: 0;
			padding: 0 0 0 0px;
		    line-height: 16px;
			display: inline-block;
			font-family: 'Calibre-Regular';
			font-weight: normal;
		}
		
		h1.title {
			font-size: 24px;
			margin-bottom: 20px;
			padding: 0 0 0 0;
			display: block;
			font-family: 'Calibre-Semibold';
			font-weight: normal;
		}
		.folderTitle {
			cursor: pointer;
		}
		div h1 {
			font-size: 17px;
			font-family: 'Calibre-Semibold';
		}
		div h2 {
			font-size: 15px;
		}
		div h3 {
			font-size: 15px;
		}
		
		
		
		.folder {
			padding: 10px 0 0 25px;
			
			 position: relative;
		}
		
		.folder:before {
		  content: "\e614";
		  display: inline-block;
		  line-height: inherit;
		  font-size: 15px;
		  font-family: "DA-brandicons";
		  vertical-align: top;
		  color: rgba(0,0,0,.2);
		  position: absolute;
		  left: 0;
		}
		
		[data-folder="1"] {
			margin-bottom: 15px;
			padding: 10px 0 0 20px;
		}
		
		[data-folder="1"]:before {
			content: "\e61f" !important;
			font-size: 17px !important;
		}
		
		
		
				
		.list {
			padding: 5px 0px 5px 0px;
			margin-bottom: 10px;
		}
		.list span.date {
			    float: right;
			    color: rgba(0,0,0,.35);	
			    font-size: 12px;	
			    line-height: 16px;
				display: inline-block;	
		}
		
		.list a {
			font-size: 14px;
			color: #666;
			text-decoration: none;
			    display: inline-block;
		}
		.list a:hover {
			color: black;
			text-decoration: underline;
		}
		
		
		.item {
			padding: 7px 0 7px 0;
			border-bottom: 1px solid rgba(0,0,0,.1);
		}
		.item:hover {
			background: rgba(255,255,255,0.5);
		}
		.item:first-of-type {
			margin-top: 5px;
			border-top: 1px solid rgba(0,0,0,.1);
		}
		
		
		
		
		
		
	</style>
	
	<section>
	
	<h1 class="title">DeviantArt Demos, Prototypes and other interesting stuff</h1>
		
		
		
	
	<?PHP
  
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
          //echo "getFileList( \"{$inFolder}\" )\n";
	      $folderList = scandir( $inFolder );
	      $fileArray = array();

		  if ( isBlockingFilePresent( $inFolder, $inPattern ) ) {
             // HTML file in this folder so scan this folder for HTML files and exit
             // Scan round list of files and insert into array keyed by name (not time code).
			 echo "<div class=\"list\" data-level=\"{$depth}\">\n";
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
                   //  echo "fullname:{$fullName}<br/>";
    		        if ( is_dir( $fullName ) ) {
	    		       
    				   // Get the time stamp based on youngest contained file. This reflects
    				   // the youngest time stamp of the contained folders/files
    				   $containedTimeStamp = getYoungestFolderTimeCode( $fullName, $inPattern );
    				   // Use this in the array of folders
                       echo "   found file [{$folder}] - " . date( "F d Y, H:i", $containedTimeStamp ) . "\n";
                       $fileArray[ $folder ] = $containedTimeStamp;
    		        }
                }
			 }
			 // At this point we have an array of folders with their time stamps so sort
             arsort( $fileArray );
             // Now traverse this recursing if necessary
			 foreach ( $fileArray as $folder => $timeStamp ) {
        		$fullName = "{$inFolder}/{$folder}";
                echo "<div class=\"folder\" data-folder=\"{$depth}\">\n";
                echo "<h{$depth} class=\"folderTitle\">{$folder}</h{$depth}>\n";
				getFileList( $fullName, $depth + 1, $inPattern );
		        echo "</div>\n";	
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
                echo "   found file [{$file}] - " . date( "F d Y, H:i", $thisTimeCode ) . "\n";
            }
         }
      }
      return $maxTimeCode;
   }

    date_default_timezone_set('Europe/London');
    // $youngestFileTime = getYoungestFolderTimeCode( "project/", "/\.html$/" );
    // echo " Youngest file - " . date( "F d Y, H:i", $youngestFileTime ) . "\n\n\n";
    
     getFileList( "projects/", 1, "/\.html$/" );
           
?>
	
	
	
	</section>
	
	
	
	
</body>
</html>