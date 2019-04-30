<?php
set_time_limit(0);

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require_once __DIR__.'/../../../vendor/autoload.php';

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $users;
	protected $usersPeruvian;
	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		$this->users[$conn->resourceId] = $conn;
		echo "New connection! ({$conn->resourceId})\n";
	}

	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		unset($this->users[$conn->resourceId]);
	}

	public function onMessage(ConnectionInterface $from,  $data) {
		$from_id = $from->resourceId;
		$data = json_decode($data);
		$type = $data->type;
		switch ($type) {
			case 'updateUserPeruvian':
				$this->usersPeruvian[$from_id] = $from;
			break;
			case 'chat':
				$user_id = $data->user_id;
                $chat_msg = $data->chat_msg;

                //$response_from = "<span style='color:#999'><b>".$user_id.":</b> ".$chat_msg."</span><br><br>";
                $response_from = "<span class='chat_msg_item chat_msg_item_user'>".$chat_msg."</span><div class='status'>20m ago</div> ";
                $response_to = "<span class='chat_msg_item chat_msg_item_admin'><div class='chat_avatar'><img src='http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg'/> </div>$chat_msg</span>";
				//$response_to = "<b>".$user_id."</b>: ".$chat_msg."<br><br>";
				// Output
				$from->send(json_encode(array("type"=>$type,"msg"=>$response_from)));
				foreach($this->usersPeruvian as $userPeruvian)
				{
					$this->users[$userPeruvian->resourceId]->send(json_encode(array("type"=>$type,"msg"=>$response_to)));
				}
				/*foreach($this->clients as $client)
				{
					if($from!=$client)
					{
						$client->send(json_encode(array("type"=>$type,"msg"=>$response_to)));
					}
				}*/
				break;
			case 'chat2':
				$user_id = $data->user_id;
				$chat_msg = $data->chat_msg;
				$response_from = "<span style='color:red'><b>".$user_id.":</b> ".$chat_msg."</span><br><br>";
				$response_to = "<b>".$user_id."</b>: ".$chat_msg."<br><br>";
				// Output
				$from->send(json_encode(array("type"=>$type,"msg"=>$response_from)));
				foreach($this->clients as $client)
				{
					if($from!=$client)
					{
						$client->send(json_encode(array("type"=>$type,"msg"=>$response_to)));
					}
				}
				break;
		}
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}
}
$server = IoServer::factory(
	new HttpServer(new WsServer(new Chat())),
	8000
);
$server->run();
?>