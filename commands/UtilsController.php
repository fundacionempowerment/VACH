<?php

namespace app\commands;

use app\models\Company;
use app\models\Person;
use app\models\TeamMember;
use app\models\Wheel;
use Faker\Factory;
use yii\console\Controller;
use app\models\Team;
use yii\console\Exception;

class UtilsController extends Controller
{

    private $wheelQuestions = [];

    /**
     * This command creates a db backup and send it to administrator.
     */
    public function actionRandomTeam($coachId, $quantity)
    {
        $company = $this->getCompany($coachId, $quantity);

        $persons = $this->getPersons($coachId, $quantity);

        $team = $this->getTeam($coachId, $company, $persons);

        echo "Team: " . $team->id . "\n";

        $this->fulfillTeam($team);

        $this->fulfillWheels($team);
    }

    private function getTeam($coachId, $company, $persons)
    {
        $faker = Factory::create();

        $team = new Team();
        $team->coach_id = $coachId;
        $team->company_id = $company->id;
        $team->name = $faker->name;
        $team->sponsor_id = $persons[0]->id;
        $team->team_type_id = 1;

        if (!$team->save()) {
            var_dump($team->getErrors());
            throw new Exception("Team not saved");
        };

        $this->bindTeamPersons($team, $persons);

        return $team;
    }

    private function bindTeamPersons($team, $persons)
    {
        foreach ($persons as $person) {
            $teamMember = new TeamMember();

            $teamMember->person_id = $person->id;
            $teamMember->team_id = $team->id;
            $teamMember->active = true;

            if (!$teamMember->save()) {
                var_dump($teamMember->getErrors());
                throw new Exception("Team member not saved");
            };
        }
    }

    private function getCompany($coachId, $quantity)
    {
        $faker = Factory::create();

        $company = new Company();
        $company->coach_id = $coachId;
        $company->name = $faker->company . " " . $quantity;
        $company->email = $faker->companyEmail;
        $company->phone = $faker->phoneNumber;

        if (!$company->save()) {
            throw new Exception("Company not saved");
        };

        echo "Company saved: " . $company->name . "\n";

        return $company;
    }

    private function getPersons($coachId, $quantity)
    {
        $persons = [];
        for ($i = 0; $i < $quantity; $i++) {
            $persons[] = $this->getPerson($coachId);
        }
        return $persons;
    }

    private function getPerson($coachId)
    {
        $faker = Factory::create();

        $person = new Person();
        $person->coach_id = $coachId;
        $person->name = $faker->name;
        $person->surname = $faker->lastName;
        $person->shortname = $faker->randomLetter . $faker->randomLetter . $faker->randomLetter;
        $person->phone = $faker->phoneNumber;
        $person->email = $faker->email;
        $person->gender = 0;

        if (!$person->save()) {
            var_dump($person->getErrors());
            throw new Exception("Team not saved");
        };
        echo "Person saved: " . $person->name . "\n";

        return $person;
    }

    private function fulfillTeam(Team $team)
    {
        foreach ($team->members as $observerMember) {
            $token = Wheel::getNewToken();
            $newWheel = new Wheel();
            $newWheel->observer_id = $observerMember->member->id;
            $newWheel->observed_id = $observerMember->member->id;
            $newWheel->type = Wheel::TYPE_INDIVIDUAL;
            $newWheel->token = $token;
            $newWheel->team_id = $team->id;
            if (!$newWheel->save()) {
                var_dump($newWheel->getErrors());
                throw new Exception("Wheel not saved");
            };
            echo "I";

            foreach ($team->members as $observedMember) {
                $newWheel = new Wheel();
                $newWheel->observer_id = $observerMember->member->id;
                $newWheel->observed_id = $observedMember->member->id;
                $newWheel->type = Wheel::TYPE_GROUP;
                $newWheel->token = $token;
                $newWheel->team_id = $team->id;
                if (!$newWheel->save()) {
                    var_dump($newWheel->getErrors());
                    throw new Exception("Wheel not saved");
                };
                echo "G";
            }

            foreach ($team->members as $observedMember) {
                $newWheel = new Wheel();
                $newWheel->observer_id = $observerMember->member->id;
                $newWheel->observed_id = $observedMember->member->id;
                $newWheel->type = Wheel::TYPE_ORGANIZATIONAL;
                $newWheel->token = $token;
                $newWheel->team_id = $team->id;
                if (!$newWheel->save()) {
                    var_dump($newWheel->getErrors());
                    throw new Exception("Wheel not saved");
                };
                echo "O";
            }
            echo "\n";
        }
    }

    private function fulfillWheels(Team $team)
    {
        echo "fulfillWheels\n";
        $team->refresh();
        foreach ($team->wheels as $wheel) {
            $this->fulfillWheel($wheel);
        }
        echo "\n";
    }

    private function fulfillWheel(Wheel $wheel)
    {
        if (!isset($this->wheelQuestions[$wheel->type])) {
            $this->wheelQuestions[$wheel->type] = $wheel->getQuestions();
        }
        $questions = $this->wheelQuestions[$wheel->type];

        $columns = [
            "wheel_id",
            "question_id",
            "dimension",
            "answer_order",
            "answer_value",
            "created_at",
            "updated_at",
        ];
        $data = [];

        foreach ($questions as $question) {
            $data[] = [
                "wheel_id" => $wheel->id,
                "question_id" => $question->question_id,
                "dimension" => $question->dimension,
                "answer_order" => $question->order,
                "answer_value" => rand(0, 4),
                "created_at" => time(),
                "updated_at" => time(),
            ];
        }

        $insertCount = \Yii::$app->db->createCommand()
            ->batchInsert("wheel_answer", $columns, $data)
            ->execute();

        if ($insertCount != count($questions)) {
            throw new Exception("Answers not saved");
        }
        echo $insertCount . " ";
    }

}
