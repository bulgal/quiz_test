<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Migrations\DependencyInjection\DependencyFactory;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240929161255 extends AbstractMigration
{

    private array $questions = [
        [
            'title' => '1 + 1 =',
            'options' => ['3', '2', '0'],
            'rightAnswersIndex' => [1]
        ],
        [
            'title' => '2 + 2 =',
            'options' => ['4', '3 + 1', '10'],
            'rightAnswersIndex' => [0, 1]
        ],
        [
            'title' => '3 + 3 =',
            'options' => ['1 + 5', '1', '6', '2 + 4'],
            'rightAnswersIndex' => [0, 2, 3]
        ],
        [
            'title' => '4 + 4 =',
            'options' => ['8', '4', '0', '0 + 8'],
            'rightAnswersIndex' => [0, 3]
        ],
        [
            'title' => '5 + 5 =',
            'options' => ['6', '18', '10', '9', '0'],
            'rightAnswersIndex' => [2]
        ],
        [
            'title' => '6 + 6 =',
            'options' => ['3', '9', '0', '12', '5 + 7'],
            'rightAnswersIndex' => [1, 3, 4]
        ],
        [
            'title' => '7 + 7 =',
            'options' => ['5', '14'],
            'rightAnswersIndex' => [1]
        ],
        [
            'title' => '8 + 8 =',
            'options' => ['16', '12', '9', '5'],
            'rightAnswersIndex' => [0]
        ],
        [
            'title' => '9 + 9 =',
            'options' => ['18', '9', '17 + 1', '2 + 16'],
            'rightAnswersIndex' => [0, 2, 3]
        ],
        [
            'title' => '10 + 10 =',
            'options' => ['0', '2', '8', '20'],
            'rightAnswersIndex' => [3]
        ],
    ];

    public function getDescription(): string
    {
        return 'Add questions';
    }

    public function up(Schema $schema): void
    {
        $id = 1;
        foreach ($this->questions as $question) {
            $this->addSql('INSERT INTO question (id, title, options, answers, created_at) VALUES (?, ?, ?, ?, ?)', [
                $id++,
                $question['title'],
                json_encode($question['options']),
                implode(',', $question['rightAnswersIndex']),
                (new \DateTimeImmutable())->format('Y-m-d H:m:s'),
            ]);
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->questions as $question) {
            $this->connection->delete('question', ['title' => $question['title']]);
        }
    }
}
