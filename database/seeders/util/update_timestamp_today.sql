UPDATE `elem_attendances`
SET created_at = CONCAT(CURDATE(), ' ', TIME(created_at))
WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY);

UPDATE `elem_attendances`
SET time_in = CONCAT(CURDATE(), ' ', TIME(time_in))
WHERE DATE(time_in) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
and status <> 'in';

UPDATE `elem_attendances`
SET time_out = CONCAT(CURDATE(), ' ', TIME(time_out))
WHERE DATE(time_out) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
and status = 'out';

UPDATE `elem_attendances`
SET updated_at = CONCAT(CURDATE(), ' ', TIME(updated_at))
WHERE DATE(updated_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
and status = 'out';