-- Optional but strongly recommended for GAME_DB transaction safety.
-- Run this against your existing TalesRunner GAME MySQL database.
-- Back up the database first. These ALTER statements may take time on large tables.

ALTER TABLE `UserInfoFromPublisher` ENGINE=InnoDB;
ALTER TABLE `UserInfoGame` ENGINE=InnoDB;
ALTER TABLE `tblGift` ENGINE=InnoDB;
