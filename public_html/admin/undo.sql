DELETE FROM JUDGED_SUBMISSIONS;
INSERT INTO JUDGED_SUBMISSIONS SELECT * FROM JUDGED_SUBMISSIONS_COPY;
DROP TABLE `JUDGED_SUBMISSIONS_COPY`;
DELETE FROM AUTO_RESPONSES;
INSERT INTO AUTO_RESPONSES SELECT * FROM AUTO_RESPONSES_COPY;
DROP TABLE `AUTO_RESPONSES_COPY`;
