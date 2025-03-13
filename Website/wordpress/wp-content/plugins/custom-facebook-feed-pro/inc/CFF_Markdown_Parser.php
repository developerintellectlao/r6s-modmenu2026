<?php

namespace CustomFacebookFeed;

use function DI\value;

/**
 * Class CFF_Markdown_Parser
 *
 * Parses markdown into HTML
 *
 * @since 4.2.5
 */
class CFF_Markdown_Parser
{
	/**
	 * RegEx Rules for parsing markdown.
	 *
	 * @var string[]
	 */
	public static $rules = array(
		'/(#+ )(.*)/'                  => 'self::header',                           // headers
		'/(\*\*|__)(.*?)\1/'           => '<strong>\2</strong>',                    // bold
		'/(\*|_)(.*?)\1/'              => '<em>\2</em>',                            // emphasis
		'/\[([^\[]+)\]\(([^\)]+)\)/'   => '<a href=\'\2\'>\1</a>',                  // links
		'/\~\~(.*?)\~\~/'              => '<del>\1</del>',                          // del
		'/\:\"(.*?)\"\:/'              => '<q>\1</q>',                              // quote
		'/`(.*?)`/'                    => '<code>\1</code>',                        // inline code
		'/\n\*(.*)/'                   => 'self::ul_list',                          // ul lists
		'/\n[0-9]+\.(.*)/'             => 'self::ol_list',                          // ol lists
		'/\n(&gt;|\>)(.*)/'            => 'self::blockquote',                       // blockquotes
		'/\n-{5,}/'                    => "\n<hr />",                               // horizontal rule
		'/\n([^\n]+)\n/'               => 'self::para',                             // add paragraphs
		'/<\/ul>\s?<ul>/'              => '',                                       // fix extra ul
		'/<\/ol>\s?<ol>/'              => '',                                       // fix extra ol
		'/<\/blockquote><blockquote>/' => "\n"                                      // fix extra blockquote
	);

	/**
	 * Parse paragraphs.
	 *
	 * @param $regs
	 *
	 * @return string
	 */
	private static function para($regs)
	{
		$line    = $regs[1];
		$trimmed = trim($line);

		if (preg_match('/^<\/?(ul|ol|li|h|p|bl)/', $trimmed)) {
			return "\n" . $line . "\n";
		}

		return sprintf("\n<p>%s</p>\n", $trimmed);
	}

	/**
	 * Parse unordered lists.
	 *
	 * @param $regs
	 *
	 * @return string
	 */
	private static function ul_list($regs)
	{
		$item = $regs[1];

		return sprintf("\n<ul>\n\t<li>%s</li>\n</ul>", trim($item));
	}

	/**
	 * Parse ordered lists.
	 *
	 * @param $regs
	 *
	 * @return string
	 */
	private static function ol_list($regs)
	{
		$item = $regs[1];

		return sprintf("\n<ol>\n\t<li>%s</li>\n</ol>", trim($item));
	}

	/**
	 * Parse blockquotes.
	 *
	 * @param $regs
	 *
	 * @return string
	 */
	private static function blockquote($regs)
	{
		$item = $regs[2];

		return sprintf("\n<blockquote>%s</blockquote>", trim($item));
	}

	/**
	 * Parse headers.
	 *
	 * @param $regs
	 *
	 * @return string
	 */
	private static function header($regs)
	{
		list ( $tmp, $chars, $header ) = $regs;
		$level = strlen($chars);

		return sprintf('<h%d>%s</h%d>', $level, trim($header), $level);
	}

	/**
	 * Adds a rule to the parser.
	 *
	 * Add a rule.
	 */
	public static function add_rule($regex, $replacement)
	{
		self::$rules[ $regex ] = $replacement;
	}

	/**
	 * Render some Markdown into HTML.
	 *
	 * @param string $text The text to parse.
	 *
	 * @return string
	 */
	public static function render($text)
	{
		$text = "\n" . $text . "\n";
		foreach (self::$rules as $regex => $replacement) {
			if (is_callable($replacement)) {
				$text = preg_replace_callback($regex, $replacement, $text);
			} else {
				$text = preg_replace($regex, $replacement, $text);
				if ($regex === '/\[([^\[]+)\]\(([^\)]+)\)/') {
					preg_match_all(
						"/a[\s]+[^>]*?href[\s]?=[\s\"\']+" .
									"(.*?)[\"\']+.*?>" . "([^<]+|.*?)?<\/a>/",
						$text,
						$matches
					);

					$matches = $matches[1];
					foreach ($matches as $single_url) {
						$text = str_replace($single_url, str_replace(array( '<em>', '</em>' ), '_', $single_url), $text);
					}
				}
			}
		}

		return trim($text);
	}
}
