<?php
class Wordconversion {
	protected $_rule = array(
		'default' => array(
				'/(s)tatus$/i' => '12tatuses',
				'/(quiz)$/i' => '1zes',
				'/^(ox)$/i' => '12en',
				'/([m|l])ouse$/i' => '1ice',
				'/(matr|vert|ind)(ix|ex)$/i'  => '1ices',
				'/(x|ch|ss|sh)$/i' => '1es',
				'/([^aeiouy]|qu)y$/i' => '1ies',
				'/(hive)$/i' => '1s',
				'/(?:([^f])fe|([lr])f)$/i' => '12ves',
				'/sis$/i' => 'ses',
				'/([ti])um$/i' => '1a',
				'/(p)erson$/i' => '1eople',
				'/(m)an$/i' => '1en',
				'/(c)hild$/i' => '1hildren',
				'/(buffal|tomat)o$/i' => '12oes',
				'/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '1i',
				'/us$/' => 'uses',
				'/(alias)$/i' => '1es',
				'/(ax|cris|test)is$/i' => '1es',
				'/s$/' => 's',
				'/^$/' => '',
				'/$/' => 's',
		),
		'special' => array(
				'atlas' => 'atlases',
				'beef' => 'beefs',
				'brother' => 'brothers',
				'child' => 'children',
				'corpus' => 'corpuses',
				'cow' => 'cows',
				'ganglion' => 'ganglions',
				'genie' => 'genies',
				'genus' => 'genera',
				'graffito' => 'graffiti',
				'hoof' => 'hoofs',
				'loaf' => 'loaves',
				'man' => 'men',
				'money' => 'monies',
				'mongoose' => 'mongooses',
				'move' => 'moves',
				'mythos' => 'mythoi',
				'niche' => 'niches',
				'numen' => 'numina',
				'occiput' => 'occiputs',
				'octopus' => 'octopuses',
				'opus' => 'opuses',
				'ox' => 'oxen',
				'penis' => 'penises',
				'person' => 'people',
				'sex' => 'sexes',
				'soliloquy' => 'soliloquies',
				'testis' => 'testes',
				'trilby' => 'trilbys',
				'turf' => 'turfs'
		),
	);

	public function __construct()
	{

	}

	public function make($str) {
		$str2 = $str;
		foreach ($this->_rule['special'] as $preg => $val) {
			if (preg_match("#{$preg}#", $str)) {
				$str2 = $val;
				return $str2;
			}
		}
		foreach ($this->_rule['default'] as $preg => $val) {
			if (preg_match($preg, $str, $matches)) {
				$str2 = preg_replace($preg,$val,$str);
			}
		}
		return $str2;
	}

	public function __destruct()
	{
		// TODO: Implement __destruct() method.
	}

}