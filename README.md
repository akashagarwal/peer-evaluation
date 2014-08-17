Peer Evaluation Mediawiki extension
===================================

This was developed as part of [Google Summer of Code](https://developers.google.com/open-source/soc/) (GSoC) 2014 for the [Open Education Resource Foundation](http://OERfoundation.org) supporting the [WikiEducator](http://WikiEducator.org) and [OERu](http://OERu.org) initiatives.

Installation instructions
=========================

### Download the extension

You may do this by directly cloning this repository using,  <pre> git clone https://gitorious.org/peer-evaluation/peer-evaluation.git </pre> inside the "*$IP/extensions/*" folder. Note: *$IP* stands for the root directory of your MediaWiki installation, the same directory that holds [LocalSettings.php](http://www.mediawiki.org/wiki/Manual:LocalSettings.php).
or, [download the latest stable version as a tar.gz](https://gitorious.org/peer-evaluation/peer-evaluation/archive/c764422fd5bfbc1b53fb78d7ea31ece2e832ba4c.tar.gz) .

In case you have downloaded the archive, create a subdirectory at "*$IP/extensions/*"  named PeerEvaluation and extract the contents in this directory.

### Installation and configuration

#### Step 1
At the end of the [LocalSettings.php](http://www.mediawiki.org/wiki/Manual:LocalSettings.php) file (but above the PHP end-of-code delimiter, *"?>"*, if present), the following line should be added:

<pre>require_once "$IP/extensions/PeerEvaluation/PeerEvaluation.php"; </pre>

If the name of your folder or path where you downloaded the extension if different from above, please change the above line accordingly.

#### Step 2

In case you have downloaded the extension at a different folder than the default, change the value of the configuration variable *"$wgPeerEvaluationHomedirPath"* located towards the end of the PeerEvaluation.php file.

#### Step 3

Add the required tables to the database as per schema.sql present in the extension folder.
You may use [the update.php script](http://www.mediawiki.org/wiki/Manual:Update.php) to do this or even manually make changes to the database.

Now you are good to go. You may follow the below quick start guide to use peer evaluation now.


