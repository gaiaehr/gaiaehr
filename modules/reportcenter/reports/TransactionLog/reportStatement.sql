SET @StartDate = :begin_date;
SET @EndDate = :end_date;

SELECT * FROM audit_transaction_log
WHERE CASE
 WHEN @StartDate IS NOT NULL AND @EndDate IS NOT NULL
 THEN date BETWEEN @StartDate AND @EndDate
 WHEN @StartDate IS NOT NULL AND @EndDate IS NULL
 THEN date BETWEEN @StartDate AND NOW()
 ELSE
 1=1
END
ORDER BY
date
LIMIT 1000
