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

If the name of your folder or path where you downloaded the extension if different from above, please change the line accordingly.

#### Step 2

In case you have downloaded the extension at a different folder than the default, change the value of the configuration variable *$wgPeerEvaluationHomedirPath* located towards the end of the *PeerEvaluation.php* file.

#### Step 3

Add the required tables to the database as per *schema.sql* present in this folder.
You may use [the update.php script](http://www.mediawiki.org/wiki/Manual:Update.php) to do this or directly make changes to the database.

Now you are good to go. You may follow the below quick start guide to use peer evaluation now.

Quick Start Quide
=================

[This wiki page contains a detailed version of the guide](http://wikieducator.org/Extension:PeerEvaluation/How_to_use_Peer_Evaluation)

#### Details about Peer Evaluation
* [Peer evaluation OERu project page](http://wikieducator.org/Peer_Evaluation)
* [The wikieducator peer evaluation extension page](http://wikieducator.org/Extension:PeerEvaluation T)

#### Overview
* Create an activity submissions page - this is the place where the submissions will be stored.
* Place the submit activity tag - this will enable you to accept submissions for an activity.
* Create a rubric as per the prescribed format and put it on a wiki page.
* Place the evaluations tag - this lets a learner view the submissions that are available for evaluation so that he can go on to evaluate them.
* Place the view evaluations tag - this lets anyone view the evaluations submitted for the activity. 

#### Creation of an activity submissions page

Create a page somewhere in the wiki, where you would want the submissions to be stored, with the following text. Make sure that this page is not deleted or modified manually after creating it for the first time. Also, it should be a new page with only the text as described below.
<pre>
===This page is created and updated automatically. Do not manually edit it.===

{|class="wikitable sortable" id="weEActivities"
!Title
!Submitted by
!class="unsortable" |Comment
!Opted in for evaluation
!Submission Time
!No of evaluations

|}
</pre>
Let the URL of the page created with the above content, relative to the home page be *urlofActivities*. We will require this URL in the later steps to accept submissions and also to view submissions available for evaluation.

#### Creation of a rubric

Now, create a wiki page which contains rubrics on which you would like to Peer Evaluate. 
[Reference for the Rubric Format](http://wikieducator.org/Extension:PeerEvaluation/RubricFormat)

We assume the URL of this page is *urlofRubric* just like that of an Activity submissions page.

#### Tag to accept submissions

Place the tag, <pre><code>&lt;submitactivity activity="urlofActivities" /&gt; </code></pre> anywhere in the wiki where you would like to accept submissions for an activity which is to be Peer evaluated. The submissions need to be a URL to a blog page/wiki page. Please specify this to your learners so that they can put whatever are the deliverables for an activity at a blog post where everyone else can see it and go on to evaluate.
This can be a part of some page with some content as well. You will just need to make sure two submitactivity tags are not placed on the same page.

#### Tag for displaying submissions available for evaluation

To accept evaluations on a wiki page, place the following tag on the page where you would want to accept the submissions , <pre><code> &lt;evaluation rubric="urlofRubric" activity="urlofActivities" /&gt; </code></pre>

#### Viewing submitted evaluations

The following tag can be used to view the submitted evaluations. <pre><code> &lt;viewevaluations activity="urlofActivities" /&gt; </code></pre>