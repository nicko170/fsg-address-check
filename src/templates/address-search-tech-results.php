<?php

use FSGAddressCheck\DTO\SiteQualificationResponse;

/** @var SiteQualificationResponse $response */

echo "<h4>Site Qualification Response</h4>";
echo "Status: " . $response->serviceabilityStatus . "<br/>";
echo "Tech: " . $response->supportingTechnology->primaryAccessTechnology . "<br/>";
echo "Class: " . $response->supportingTechnology->serviceabilityClass . "<br/>";
echo "New Development Charge Applies? ";
echo $response->supportingRelatedLocationFeatures->newDevelopmentsChargeApplies === true ? 'Yes' : 'No';
echo "<br/>";

if ($response->supportingResources) {
	echo "<h4>Supporting Resources</h4>";
	foreach ($response->supportingResources as $resource) {
		echo "Resource: $resource->id ($resource->type) - $resource->NBNServiceStatus" . "<br/>";
	}
}

if($response->supportingTechnology->alternativeTechnology) {
	echo "<h4>Alternative Technology</h4>";
	echo "You could upgrade to " . $response->supportingTechnology->alternativeTechnology;
}
