-- Per-series tier discounts (CD, CX, DN) held in the database.
-- series_discount_overrides.discount_pct is the % off list for that series on that tier.
-- Each discount_XX_jun_2026 view below left-joins it; no row => the view's normal multiplier.
-- Idempotent: safe to re-run. Run on each environment BEFORE deploying the theme code.
-- jan_2026 views are not touched (nothing reads them). New price periods need the same
-- view pattern: copy a block below and change the period.

CREATE TABLE IF NOT EXISTS series_discount_overrides (
  series       VARCHAR(20)  NOT NULL,
  tier         VARCHAR(30)  NOT NULL,
  discount_pct DECIMAL(5,2) NOT NULL,
  PRIMARY KEY (series, tier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

REPLACE INTO series_discount_overrides (series, tier, discount_pct) VALUES
  ('CD','direct_30',10.00),
  ('CD','direct_42_5',15.00),
  ('CD','57_5',25.00),
  ('CD','MSC_PL',25.00),
  ('CD','ploem_60',20.00),
  ('CD','ploem_65',20.00),
  ('CD','t3',20.00),
  ('CD','t2',20.00),
  ('CD','t1',20.00),
  ('CD','exemptPlus',20.00),
  ('CD','57',25.00),
  ('CD','tier_57',25.00),
  ('CD','pl_55',20.00),
  ('CD','pl_57',25.00),
  ('CX','direct_30',10.00),
  ('CX','direct_42_5',15.00),
  ('CX','57_5',25.00),
  ('CX','MSC_PL',25.00),
  ('CX','ploem_60',20.00),
  ('CX','ploem_65',20.00),
  ('CX','t3',20.00),
  ('CX','t2',20.00),
  ('CX','t1',20.00),
  ('CX','exemptPlus',20.00),
  ('CX','57',25.00),
  ('CX','tier_57',25.00),
  ('CX','pl_55',20.00),
  ('CX','pl_57',25.00),
  ('DN','direct_30',10.00),
  ('DN','direct_42_5',15.00),
  ('DN','57_5',25.00),
  ('DN','MSC_PL',25.00),
  ('DN','ploem_60',20.00),
  ('DN','ploem_65',20.00),
  ('DN','t3',20.00),
  ('DN','t2',20.00),
  ('DN','t1',20.00),
  ('DN','exemptPlus',20.00),
  ('DN','57',25.00),
  ('DN','tier_57',25.00),
  ('DN','pl_55',20.00),
  ('DN','pl_57',25.00);

CREATE OR REPLACE VIEW discount_42_5_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.575) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 'direct_42_5';

CREATE OR REPLACE VIEW discount_50_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.5) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 't3';

CREATE OR REPLACE VIEW discount_52_5_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.475) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 't2';

CREATE OR REPLACE VIEW discount_55_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.45) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 't1';

CREATE OR REPLACE VIEW discount_55_25_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100) * 1.25, 0.5625) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 'exemptPlus';

CREATE OR REPLACE VIEW discount_57_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.43) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = '57';

CREATE OR REPLACE VIEW discount_57_5_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.425) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = '57_5';

CREATE OR REPLACE VIEW discount_60_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.4) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 'ploem_60';

CREATE OR REPLACE VIEW discount_65_jun_2026 AS
SELECT
  m.part AS part,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS net_price,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.35) AS DECIMAL(10,2)) AS qty_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 'ploem_65';

CREATE OR REPLACE VIEW discount_direct_jun_2026 AS
SELECT
  m.part AS PART,
  m.SERIES AS SERIES,
  m.TOOL_TYPE AS TOOL_TYPE,
  m.FAMILY AS FAMILY,
  m.BRAND AS BRAND,
  m.FULL_DESCRIPTION AS FULL_DESCRIPTION,
  m.LIST_PRICE AS LIST_PRICE,
  CAST(m.LIST_PRICE * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS NET_PRICE,
  CAST(m.LIST_QTY_1 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_1,
  CAST(m.LIST_QTY_2 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_2,
  CAST(m.LIST_QTY_3 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_3,
  CAST(m.LIST_QTY_6 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_6,
  CAST(m.LIST_QTY_9 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_9,
  CAST(m.LIST_QTY_12 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_12,
  CAST(m.LIST_QTY_24 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_24,
  CAST(m.LIST_QTY_48 * COALESCE((1 - o.discount_pct / 100), 0.70) AS DECIMAL(10,2)) AS QTY_48
FROM master_price_data_jun_2026 m
LEFT JOIN series_discount_overrides o ON o.series = m.SERIES AND o.tier = 'direct_30';
