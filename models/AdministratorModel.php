<?php
    namespace App\Models;

    use App\Core\DatabaseConnection;
    use App\Core\Model;
    use App\Validators\NumberValidator;
    use App\Validators\DateTimeValidator;
    use App\Validators\StringValidator;
    use App\Validators\BitValidator;
    use App\Core\Field;

    class AdministratorModel extends Model{
        protected function getFields(): array {
            return [
                'administrator_id'   => new Field((new NumberValidator())->setIntegerLength(10), false),
                'created_at'         => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),
                'username'           => new Field((new StringValidator())->setMaxLength(128)),
                'password'           => new Field((new StringValidator())->setMaxLength(128)),
                'is_active'          => new Field( new BitValidator() )
            ];
        }

        public function getByUsername(string $username) {
            return $this->getByFieldName('username', $username);
        }

        public function check(string $username, string $password) {
            $sql = 'SELECT * FROM administrator WHERE username = ? AND password = ? ;';
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$username, $password]);
            $item = null;
            if($res){
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }
            return $item;
        }

    }