<?php declare (strict_types=1);

namespace Sabre\DAVACL\Exception;

use Sabre\DAV;

class NoAbstractTest extends \PHPUnit_Framework_TestCase {

    function testSerialize() {

        $ex = new NoAbstract('message');

        $server = new DAV\Server(null, null, null, function(){});
        $dom = new \DOMDocument('1.0', 'utf-8');
        $root = $dom->createElementNS('DAV:', 'd:root');
        $dom->appendChild($root);

        $ex->serialize($server, $root);

        $xpaths = [
            '/d:root'               => 1,
            '/d:root/d:no-abstract' => 1,
        ];

        // Reloading because PHP DOM sucks
        $dom2 = new \DOMDocument('1.0', 'utf-8');
        $dom2->loadXML($dom->saveXML());

        $dxpath = new \DOMXPath($dom2);
        $dxpath->registerNamespace('d', 'DAV:');
        foreach ($xpaths as $xpath => $count) {

            $this->assertEquals($count, $dxpath->query($xpath)->length, 'Looking for : ' . $xpath . ', we could only find ' . $dxpath->query($xpath)->length . ' elements, while we expected ' . $count);

        }

    }

}
