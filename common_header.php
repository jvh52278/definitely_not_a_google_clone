<html>
    <body>
        <!-- div containing common header -->
        <div id="common_header">
            <!-- logo section -> left section -->
            <div id="left_section">
                <!-- link to the main page -->
                <a href="./main.php">
                    <!-- make an image the link instead of a text link -->
                    <img src="./images/not_youtube_logo.png" alt="logo" height="60px" width="150px">
                </a>
            </div>
            <!-- search bar section -> middle section -->
            <div id="middle_section">
                <!-- form containing search bar -->
                <form action="" id="search_bar" name="search_bar" method="get">
                    <input name="search_terms" id="search_terms" type="search"> <!--input for the search terms-->
                    <input name="search_bar_submit" id="search_bar_submit" type="submit" value="Search"> <!-- submit button for the search bar -->
                </form>
            </div>
            <!-- link section -> right section -->
            <div id="right_section">
                <!-- div containing the links -->
                <div id="links_section">
                    <a href="./login.php">Login</a> <!-- login link -->
                    <a href="./manage_account.php">Manage account</a> <!-- link to user account page -->
                </div>
            </div>
        </div>
    </body>
    <style>
        #common_header {
            /*background-color: orange; /* set background color */
            display: flex; /* make the three sections of the header display side by side */
            border-style: solid;
            border-top: none;
            border-left: none;
            border-right: none;
            border-color: var(--primary_border_color_1);
        }
        #left_section {
            flex: 1; /* make the left section take up 1/3 of the width of the common header */
            background-color: var(--primary_element_background); /* set background color */
            border-style: solid;
            border-color: var(--primary_border_color_1);
        }
        #middle_section {
            flex: 1; /* make the middle section take up 1/3 of the width of the common header */
        }
        #right_section {
            flex: 1; /* make the right section take up 1/3 of the width of the common header */
        }
        #middle_section #search_terms {
            width: 70%; /* make the width of search bar 70% of the width of the subsection */
            height: 30px; /* make the height of the search bar 30px */
        }
        #middle_section #search_bar_submit {
            font-size: 20px; /* increase the font size of the submit button to 20px */;
            background-color: var(--primary_element_background); /* set background color */
            border-color: var(--primary_border_color_1);
            border-radius: 2px;
        }
        #middle_section #search_bar {
            margin: 10px; /* add 10px of space to the top, bottom, left and right of the search bar section */
        }
        #right_section #links_section {
            float: right; /* right allign the links */
            margin: 10px; /* add 10px of space to the top, bottom, left and right of the links section */
            padding: 5px; /* add 5 px of visible space inwards to the top, bottom, left and right of the links section */
        }
        #right_section #links_section a {
            text-decoration: none; /* for the links in the links section, remove the underline */
            color: var(--primary_text_color_2);/* for the links in the links section, make the font black */
            font-size: 20px; /* for the links in the links section, increase the font to 20px */
            margin-right: 10px; /* for the links in the links section, add 10px to the right side */
            margin-left: 10px; /* for the links in the links section, add 10px to the left side */
            padding: 10px; /* add 10 px of visible space inwards to the top, bottom, left and right of each link */
            background-color: var(--primary_element_background); /* set background color */
            border-color: var(--primary_border_color_1);
            border-radius: 2px;
        }
    </style>
</html>