
-- Données : 2 utilisateurs
INSERT INTO users (username, password) VALUES
('alice', 'password123'),
('bob', 'azerty');

-- Conversation entre Alice (id=1) et Bob (id=2)
INSERT INTO conversations (user1_id, user2_id, last_message_time) VALUES
(1, 2, NOW());

-- Messages échangés
INSERT INTO messages (conversation_id, sender_id, contenu, created_at) VALUES
(1, 1, 'Salut Bob !', NOW() - INTERVAL 2 HOUR),
(1, 2, 'Salut Alice, ça va ?', NOW() - INTERVAL 1 HOUR - 55 MINUTE),
(1, 1, 'Oui très bien et toi ?', NOW() - INTERVAL 1 HOUR - 30 MINUTE),
(1, 2, 'Impeccable !', NOW() - INTERVAL 1 HOUR),
(1, 1, 'Tu fais quoi ce soir ?', NOW() - INTERVAL 30 MINUTE),
(1, 2, 'Rien de prévu, et toi ?', NOW() - INTERVAL 15 MINUTE),
(1, 1, 'Cinéma ?', NOW() - INTERVAL 5 MINUTE),
(1, 2, 'Bonne idée !', NOW());