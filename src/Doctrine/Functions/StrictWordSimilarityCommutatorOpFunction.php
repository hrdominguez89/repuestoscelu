<?php

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class StrictWordSimilarityCommutatorOpFunction extends FunctionNode
{
    public $firstStringExpression = null;
    public $secondStringExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        // "strict_word_similarity_commutator_op" "(" StringPrimary "," StringPrimary ")"
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstStringExpression = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondStringExpression = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'strict_word_similarity_commutator_op(' .
            $this->firstStringExpression->dispatch($sqlWalker) . ', ' .
            $this->secondStringExpression->dispatch($sqlWalker) .
            ')';
    }
}
