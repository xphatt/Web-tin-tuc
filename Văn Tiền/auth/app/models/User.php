<?php

class User
{
    public const ROLES = ['admin', 'editor'];
    public const STATUSES = ['active', 'locked'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    public function all(): array
    {
        $sql = 'SELECT id, username, email, full_name, role, status, created_at
                FROM users ORDER BY id DESC';

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $st->execute(['id' => $id]);

        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByLogin(string $login): ?array
    {
        $st = $this->db->prepare(
            'SELECT * FROM users WHERE username = :u OR email = :e LIMIT 1'
        );
        $st->execute(['u' => $login, 'e' => $login]);

        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function usernameExists(string $username, int $exceptId = 0): bool
    {
        $st = $this->db->prepare('SELECT 1 FROM users WHERE username = :v AND id <> :id');
        $st->execute(['v' => $username, 'id' => $exceptId]);

        return (bool) $st->fetchColumn();
    }

    public function emailExists(string $email, int $exceptId = 0): bool
    {
        $st = $this->db->prepare('SELECT 1 FROM users WHERE email = :v AND id <> :id');
        $st->execute(['v' => $email, 'id' => $exceptId]);

        return (bool) $st->fetchColumn();
    }

    public function create(array $d, string $plainPassword): int
    {
        $st = $this->db->prepare(
            'INSERT INTO users (username, email, password_hash, full_name, role, status)
             VALUES (:username, :email, :hash, :full_name, :role, :status)'
        );
        $st->execute([
            'username'  => $d['username'],
            'email'     => $d['email'],
            'hash'      => password_hash($plainPassword, PASSWORD_BCRYPT),
            'full_name' => $d['full_name'],
            'role'      => $d['role'],
            'status'    => 'active',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $st = $this->db->prepare(
            'UPDATE users SET username = :username, email = :email,
             full_name = :full_name, role = :role, updated_at = NOW() WHERE id = :id'
        );
        $st->execute([
            'username'  => $d['username'],
            'email'     => $d['email'],
            'full_name' => $d['full_name'],
            'role'      => $d['role'],
            'id'        => $id,
        ]);
    }

    public function updatePassword(int $id, string $plainPassword): void
    {
        $st = $this->db->prepare(
            'UPDATE users SET password_hash = :h, updated_at = NOW() WHERE id = :id'
        );
        $st->execute(['h' => password_hash($plainPassword, PASSWORD_BCRYPT), 'id' => $id]);
    }

    public function setStatus(int $id, string $status): void
    {
        $st = $this->db->prepare('UPDATE users SET status = :s, updated_at = NOW() WHERE id = :id');
        $st->execute(['s' => $status, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $st->execute(['id' => $id]);
    }

    public function paginate(int $page, int $perPage): array
    {
        $total = (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();

        $st = $this->db->prepare(
            'SELECT id, username, email, full_name, role, status, created_at
             FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset'
        );
        $st->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $st->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $st->execute();

        return [$st->fetchAll(PDO::FETCH_ASSOC), $total];
    }

    public function countArticles(int $id): int
    {
        $st = $this->db->prepare('SELECT COUNT(*) FROM articles WHERE author_id = :id');
        $st->execute(['id' => $id]);

        return (int) $st->fetchColumn();
    }
}
