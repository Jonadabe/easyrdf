<?php

/**
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009-2010 Nicholas J Humfrey.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. The name of the author 'Nicholas J Humfrey" may be used to endorse or
 *    promote products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009-2010 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @version    $Id$
 */

/**
 * Class to allow serialising to RDF using the ARC2 library.
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009-2010 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class EasyRdf_Serialiser_Arc extends EasyRdf_Serialiser_RdfPhp
{
    private static $_supportedTypes = array(
        'rdfxml' => 'RDFXML',
        'turtle' => 'Turtle',
        'ntriples' => 'NTriples',
        'posh' => 'POSHRDF'
    );

    /**
     * Constructor
     *
     * @return object EasyRdf_Serialiser_Arc
     */
    public function __construct()
    {
        require_once 'arc/ARC2.php';
    }

    /**
     * Serialise an EasyRdf_Graph into RDF format of choice.
     *
     * @param string $graph An EasyRdf_Graph object.
     * @param string $format The name of the format to convert to.
     * @return string The RDF in the new desired format.
     */
    public function serialise($graph, $format)
    {
        parent::checkSerialiseParams($graph, $format);

        if (array_key_exists($format, self::$_supportedTypes)) {
            $className = self::$_supportedTypes[$format];
        } else {
            throw new EasyRdf_Exception(
                "Serialising documents to $format ".
                "is not supported by EasyRdf_Serialiser_Arc."
            );
        }

        $serialiser = ARC2::getSer($className);
        if ($serialiser) {
            return $serialiser->getSerializedIndex(
                parent::serialise($graph, 'php')
            );
        } else {
            throw new EasyRdf_Exception(
                "ARC2 failed to get a $className serialiser."
            );
        }
    }
}

# FIXME: to this automatically
EasyRdf_Format::register('posh', 'poshRDF');
EasyRdf_Format::registerSerialiser('ntriples', 'EasyRdf_Serialiser_Arc');
EasyRdf_Format::registerSerialiser('posh', 'EasyRdf_Serialiser_Arc');
EasyRdf_Format::registerSerialiser('rdfxml', 'EasyRdf_Serialiser_Arc');
EasyRdf_Format::registerSerialiser('turtle', 'EasyRdf_Serialiser_Arc');
