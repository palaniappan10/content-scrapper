$MainPage = file_get_html('http://gadgets.ndtv.com/news/');

	foreach($MainPage->find('tr') as $tr) 
	{
		foreach ($tr->find('a') as $NewsDetail) {
			$NewsLink = "http://gadgets.ndtv.com" . $NewsDetail->href;
			$News = file_get_html($NewsLink);

			foreach($News->find('img#HeadContent_FullstoryCtrl_mainstoryimage') as $FeatureImage)
			{
				$FeatureImage = $FeatureImage->src;

				ini_set('max_execution_time', 300);

				foreach($News->find('div#HeadContent_FullstoryCtrl_fulldetails') as $ActualNews)

			    	$Author = $News->find('span#HeadContent_FullstoryCtrl_byline' , 0)->plaintext;
			    	$Date = $News->find('span#HeadContent_FullstoryCtrl_date' , 0)->plaintext;

	    		$Date = mysqli_real_escape_string($conn, $Date);
		    	$Author = mysqli_real_escape_string($conn, $Author);
		    	$ActualNews = mysqli_real_escape_string($conn, $ActualNews);

		    	$check_date = mysqli_query($conn,"select max(posted_date) from news_populate.ndtv_tech");

			    	if ($Date > $check_date or $check_date!=0) 
			    	{
			    		mysqli_select_db($conn,'news_populate'); 
						$insert="INSERT INTO news_populate.ndtv_tech (posted_date,author,image,content) VALUES ('$Date','$Author','$FeatureImage','$ActualNews')";
						if (!mysqli_query($conn, $insert)) 
						{
						  echo('error: ' . mysqli_error($conn)); 
						}
						else
						{
						  // echo "success";
						}
			    	}
			    	else
			    	{
			    		echo "no need to insert";
			    	}
			}
		}
	}