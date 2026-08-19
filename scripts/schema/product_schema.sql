-- =============================================================================
-- ISO 13399-aligned product schema
--
-- Replaces the 128-column, all-varchar `master_product_data` with:
--   product_core            universal columns + universal dimensions
--   <type>_attributes       per-tool-type specifics, PART as the index
--   product_variants        the 30-column coating/finish part-number matrix
--   attribute_dictionary    the legend: labels, ISO codes, units, legacy mapping
--   dimension_precision_exceptions   rows where 4dp storage rounded a true value
--
-- ADDITIVE ONLY. `master_product_data` is untouched; staging and production share
-- this database, so nothing here is visible to the running site.
--
-- Naming: UPPERCASE ISO 13399 property codes where one exists, with _DISPLAY for
-- the presentation string and a generated _IN for inch-denominated filtering.
-- Attributes with no ISO 13399 code use descriptive UPPER_SNAKE_CASE; every such
-- case is flagged CODE_VERIFIED='N' in attribute_dictionary.
--
-- Units: values are stored in the ISO 13399 base unit - millimetres for lengths,
-- degrees for angles, dimensionless for counts. The unit is declared once per
-- attribute in attribute_dictionary, never per row, so a column cannot hold mixed
-- units (the defect in the legacy `outer_dimension`).
--
-- Verified codes come from the Dormer Pramet / Sandvik Coromant ISO 13399
-- parameter dictionaries.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- product_core
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_core` (
  `PART`               varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `SERIES`             varchar(25)  DEFAULT NULL,
  `FAMILY`             varchar(30)  DEFAULT NULL,
  `BRAND`              varchar(20)  DEFAULT NULL,
  `TOOL_TYPE`          varchar(16)  DEFAULT NULL COMMENT 'Case-normalised: MILLING/HOLEMAKING/THREADING/SPECIALTY/INSERTS/ARMORY',
  `SUB_TYPE`           varchar(75)  DEFAULT NULL,
  `SUB_SUB_TYPE`       varchar(75)  DEFAULT NULL,
  `PART_DESCRIPTION`   varchar(200) DEFAULT NULL,
  `COUNTRY_OF_ORIGIN`  char(3)      DEFAULT NULL COMMENT 'ISO 3166-1 alpha-3',
  `NATIVE_UNIT_SYSTEM` enum('INCH','METRIC') DEFAULT NULL COMMENT 'Display preference only; storage unit is always the ISO base unit',
  `COATING`            varchar(15)  DEFAULT NULL COMMENT 'ISO 13399 COATING',
  `GRADE`              varchar(11)  DEFAULT NULL COMMENT 'Grade designation, e.g. CG88',
  `SUBSTRATE`          varchar(14)  DEFAULT NULL COMMENT 'ISO 13399 SUBSTRATE - HSS/Carbide/PM (legacy taps_material)',
  `TSYC`               varchar(25)  DEFAULT NULL COMMENT 'ISO 13399 TSYC - Tool style code; legacy style, spans 4 tool types so it lives here',
  `EDGE_PREPARATION`   varchar(8)   DEFAULT NULL COMMENT 'Legacy edge_prep (milling + inserts); no ISO 13399 code',
  `IS_WEB_VISIBLE`     tinyint(1)   NOT NULL DEFAULT 0,
  `IS_IN_CATALOG`      tinyint(1)   NOT NULL DEFAULT 0,
  `SORT_ORDER`         smallint(6)  DEFAULT 0,
  `CUSTOM_IMAGE_SLUG`  varchar(10)  DEFAULT NULL,

  -- Universal dimensions. Each: value in the ISO base unit, verbatim display
  -- form, and a generated inch value. Generated columns cannot drift from the
  -- value they derive from, which is why no *_MM/*_IN pair is stored.
  `DC`             decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 DC - Cutting diameter (mm)',
  `DC_DISPLAY`     varchar(80)  DEFAULT NULL,
  `DC_IN`          decimal(11,6) AS (`DC` / 25.4) VIRTUAL,

  `DMM`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 DMM - Shank diameter (mm)',
  `DMM_DISPLAY`    varchar(80)  DEFAULT NULL,
  `DMM_IN`         decimal(11,6) AS (`DMM` / 25.4) VIRTUAL,

  `OAL`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 OAL - Overall length (mm)',
  `OAL_DISPLAY`    varchar(80)  DEFAULT NULL,
  `OAL_IN`         decimal(11,6) AS (`OAL` / 25.4) VIRTUAL,

  `LCF`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 LCF - Length chip flute (mm)',
  `LCF_DISPLAY`    varchar(80)  DEFAULT NULL,
  `LCF_IN`         decimal(11,6) AS (`LCF` / 25.4) VIRTUAL,

  `RE`             decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 RE - Corner radius (mm)',
  `RE_DISPLAY`     varchar(80)  DEFAULT NULL,
  `RE_IN`          decimal(11,6) AS (`RE` / 25.4) VIRTUAL,

  `LPR`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 LPR - Protruding length / reach (mm)',
  `LPR_DISPLAY`    varchar(80)  DEFAULT NULL,
  `LPR_IN`         decimal(11,6) AS (`LPR` / 25.4) VIRTUAL,

  `SIG`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 SIG - Point angle (degrees)',
  `NOF`            tinyint(4)   DEFAULT NULL COMMENT 'ISO 13399 NOF - Flute count',
  `FHA`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 FHA - Flute helix angle (degrees)',
  `FHA_DISPLAY`    varchar(32)  DEFAULT NULL COMMENT 'Non-numeric helix values that have no numeric equivalent: Variable (855 rows), N (81)',

  PRIMARY KEY (`PART`),
  KEY `idx_core_series`    (`SERIES`),
  KEY `idx_core_type`      (`TOOL_TYPE`, `SUB_TYPE`, `SUB_SUB_TYPE`),
  KEY `idx_core_family`    (`FAMILY`),
  KEY `idx_core_web`       (`IS_WEB_VISIBLE`),
  KEY `idx_core_dc`        (`DC`),
  KEY `idx_core_dc_in`     (`DC_IN`),
  KEY `idx_core_dmm_in`    (`DMM_IN`),
  KEY `idx_core_oal_in`    (`OAL_IN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- milling_attributes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `milling_attributes` (
  `PART`             varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `STEP_DC`          decimal(9,4) DEFAULT NULL COMMENT 'Step diameter (mm) - legacy step_dia',
  `STEP_DC_DISPLAY`  varchar(32)  DEFAULT NULL,
  `SDL_1`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 SDL - Step diameter length 1 (mm), legacy l1',
  `SDL_1_DISPLAY`    varchar(32)  DEFAULT NULL,
  `SDL_2`            decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 SDL - Step diameter length 2 (mm), legacy l2',
  `SDL_2_DISPLAY`    varchar(32)  DEFAULT NULL,
  `CHW`              decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 CHW - Corner chamfer width (mm); legacy chamfer, milling rows only',
  `ULDR`             decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 ULDR - Usable length diameter ratio; legacy ldr is written "3X"',
  `HAS_WELDON_FLAT`  tinyint(1)   DEFAULT NULL COMMENT 'No ISO 13399 code exists (WT is Weight of item)',
  `DRIVE_TYPE`       varchar(7)   DEFAULT NULL COMMENT 'Legacy torx_type; no ISO code',
  PRIMARY KEY (`PART`),
  CONSTRAINT `fk_milling_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- holemaking_attributes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `holemaking_attributes` (
  `PART`                varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `PILOT_DC`            decimal(9,4) DEFAULT NULL COMMENT 'Pilot diameter (mm)',
  `PILOT_DC_DISPLAY`    varchar(32)  DEFAULT NULL,
  `DMIN`                decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 DMIN - Minimum bore diameter (mm)',
  `DMIN_DISPLAY`        varchar(32)  DEFAULT NULL,
  `BORE_MAX`            decimal(9,4) DEFAULT NULL COMMENT 'Maximum bore diameter (mm); no verified ISO code',
  `BORE_MAX_DISPLAY`    varchar(32)  DEFAULT NULL,
  `LPR`                 decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 LPR - Protruding length (mm); legacy projection',
  `HAS_DRILL_POINT`     tinyint(1)   DEFAULT NULL COMMENT 'Legacy drillpoint holds Yes/No, not a point type',
  `PAC_DRILL_SIZE`      varchar(7)   DEFAULT NULL,
  `DRILL_GAUGE`         varchar(16)  DEFAULT NULL COMMENT 'Letter/number drill sizes such as #52, P, S',
  `SHANK_THREAD_TDZ`    varchar(24)  DEFAULT NULL COMMENT 'Threaded-shank designation, e.g. 1/4-28; legacy `thread` holds a size, not a flag',
  PRIMARY KEY (`PART`),
  CONSTRAINT `fk_holemaking_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- threading_attributes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `threading_attributes` (
  `PART`                  varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `TAP_CATEGORY`          varchar(15)  DEFAULT NULL COMMENT 'Legacy taps_sub_cat. Differs from SUB_SUB_TYPE in all 5,055 rows (e.g. "Hand Tap" + "LH"), so preserved rather than folded',
  `TDZ`                   varchar(24)  DEFAULT NULL COMMENT 'ISO 13399 TDZ - Thread diameter size, e.g. 1/4-20, M10',
  `NOMINAL_SIZE`          varchar(16)  DEFAULT NULL COMMENT 'Nominal size portion of the thread designation, e.g. #8 where TDZ is 8-32; recovered from the misused cut_dia_in_display column',
  `PIPE_TDZ`              varchar(24)  DEFAULT NULL COMMENT 'Pipe thread designation',
  `TPI`                   decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 TPI - Threads per inch',
  `THFT`                  varchar(12)  DEFAULT NULL COMMENT 'ISO 13399 THFT - Form type: UNC/UNF/UNS/M/NPT (ISO 68-1)',
  `THREAD_DIRECTION`      enum('RH','LH') DEFAULT NULL COMMENT 'No ISO 13399 hand-of-cut code exists',
  `DN`                    decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 DN - Neck diameter (mm); all 52 populated legacy rows are taps, not milling',
  `DN_DISPLAY`            varchar(32)  DEFAULT NULL,
  `TCTR`                  varchar(8)   DEFAULT NULL COMMENT 'ISO 13399 TCTR - Thread tolerance class; legacy taps_thread_limit',
  `CLASS_OF_FIT`          varchar(15)  DEFAULT NULL,
  `THCHT`                 varchar(26)  DEFAULT NULL COMMENT 'ISO 13399 THCHT - Threading chamfer type; ISO 529 form',
  `CHAMFER_LEAD_THREADS`  decimal(9,4) DEFAULT NULL COMMENT 'Chamfer lead expressed in threads; legacy chamfer, threading rows',
  `STANDARD_REF`          varchar(16)  DEFAULT NULL COMMENT 'ANSI / STD / DIN 40430; recovered from taps_standard and misfiled oal/shank columns',
  `TAP_DRILL_MIN`         decimal(9,4) DEFAULT NULL COMMENT 'Minimum tap drill size (mm)',
  `TAP_DRILL_MIN_DISPLAY` varchar(32)  DEFAULT NULL,
  `TAP_DRILL_MAX`         decimal(9,4) DEFAULT NULL COMMENT 'Maximum tap drill size (mm)',
  `TAP_DRILL_MAX_DISPLAY` varchar(32)  DEFAULT NULL,
  `THLGTH`                decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 THLGTH - Thread length (mm)',
  `THLGTH_DISPLAY`        varchar(32)  DEFAULT NULL,
  `DRILL_LENGTH`          decimal(9,4) DEFAULT NULL COMMENT 'Drill length (mm)',
  `DRILL_LENGTH_DISPLAY`  varchar(32)  DEFAULT NULL,
  `SQUARE_SIZE`           decimal(9,4) DEFAULT NULL COMMENT 'Square drive size (mm); no ISO code',
  `SQUARE_SIZE_DISPLAY`   varchar(32)  DEFAULT NULL,
  `DCX`                   decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 DCX - Cutting diameter maximum (mm); legacy taps_od1',
  `CNSC`                  varchar(3)   DEFAULT NULL COMMENT 'ISO 13399 CNSC - Coolant entry style code',
  `GAGE_MEMBER`           varchar(7)   DEFAULT NULL COMMENT 'Go / NoGo; no ISO code',
  `HAS_HANDLE`            tinyint(1)   DEFAULT NULL,
  `GAGE_BODY`             varchar(9)   DEFAULT NULL COMMENT 'Legacy taps_body',
  `GAGE_DEPTH`            decimal(9,4) DEFAULT NULL COMMENT 'Legacy taps_depth (mm)',
  `GAGE_DIA`              decimal(9,4) DEFAULT NULL COMMENT 'Legacy taps_dia (mm)',
  `PACKAGE_SIZE`          varchar(16)  DEFAULT NULL COMMENT 'Fluids pack size, e.g. 1 GAL / 16 OZ; legacy taps_amount',
  `TAP_SIZE_RANGE`        varchar(16)  DEFAULT NULL COMMENT 'Metric tap size range a handle/extension accepts, e.g. M8/M11; legacy taps_size',
  `ACCEPTS_TAP_SIZE`      varchar(24)  DEFAULT NULL COMMENT 'Inch tap size a handle/wrench accepts, e.g. 5/16, #12; legacy taps_tap_size',
  PRIMARY KEY (`PART`),
  KEY `idx_threading_tdz`  (`TDZ`),
  KEY `idx_threading_thft` (`THFT`),
  CONSTRAINT `fk_threading_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- inserts_attributes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inserts_attributes` (
  `PART`              varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ISO_DESIGNATION`   varchar(39)  DEFAULT NULL COMMENT 'ISO 1832 designation, e.g. RNGN-090300',
  `SHAPE_CODE`        varchar(24)  DEFAULT NULL COMMENT 'ISO 1832 shape code; legacy `designation` is unusable (holds the series number)',
  `IC`                decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 IC - Inscribed circle diameter (mm)',
  `IC_DISPLAY`        varchar(32)  DEFAULT NULL,
  `S`                 decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 S - Insert thickness (mm)',
  `S_DISPLAY`         varchar(32)  DEFAULT NULL,
  `W1`                decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 W1 - Insert width (mm)',
  `W1_DISPLAY`        varchar(32)  DEFAULT NULL,
  `MOUNTING_STYLE`    varchar(50)  DEFAULT NULL COMMENT 'Flat Top / V Bottom / With Hole / Dimple; no ISO code',
  PRIMARY KEY (`PART`),
  CONSTRAINT `fk_inserts_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- specialty_attributes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `specialty_attributes` (
  `PART`               varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `BURR_DESIGNATION`   varchar(13)  DEFAULT NULL COMMENT 'ANSI B94.53, e.g. SA-61 - not an ISO standard',
  `CUT_STYLE`          varchar(16)  DEFAULT NULL COMMENT 'Single Cut / Double Cut / Aluma Cut; from legacy c2 text rows',
  `SPLIT_LENGTH`       decimal(9,4) DEFAULT NULL COMMENT 'Split length L1 (mm); legacy `split` is a packed length, not a category',
  `SPLIT_LENGTH_DISPLAY` varchar(32) DEFAULT NULL,
  `HTH`                decimal(9,4) DEFAULT NULL COMMENT 'ISO 13399 HTH - Height (mm)',
  `HTH_DISPLAY`        varchar(32)  DEFAULT NULL,
  `S`                  decimal(9,4) DEFAULT NULL COMMENT 'Thickness (mm) - blanks/strips',
  `S_DISPLAY`          varchar(32)  DEFAULT NULL,
  `W1`                 decimal(9,4) DEFAULT NULL COMMENT 'Width (mm) - blanks/strips',
  `W1_DISPLAY`         varchar(32)  DEFAULT NULL,
  PRIMARY KEY (`PART`),
  CONSTRAINT `fk_specialty_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- armory_attributes
--
-- Intentionally empty. Verified across all 18 ARMORY parts: every populated
-- legacy column belongs to product_core or the variant matrix, so there is no
-- type-specific attribute to store. The table exists as the declared extension
-- point for the type - the migration writes no rows rather than 18 rows of NULLs.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `armory_attributes` (
  `PART`   varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `NOTES`  varchar(200) DEFAULT NULL,
  PRIMARY KEY (`PART`),
  CONSTRAINT `fk_armory_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- product_variants
--
-- Replaces 30 legacy columns (none/altin/fx1/.../taps_plug/taps_bottom) that held
-- sibling part numbers rather than attributes. The legacy matrix was
-- self-inclusive: a row listed all its siblings including itself.
--
-- No FK on VARIANT_PART deliberately: 2 legacy threading references point at part
-- numbers absent from the catalogue. A FK would silently reject those rows; an
-- index plus an explicit verification query surfaces them instead.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_variants` (
  `PART`          varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'The part whose siblings these are',
  `VARIANT_AXIS`  varchar(24) NOT NULL COMMENT 'COATING | SURFACE_CONDITION | CUT_STYLE | CHAMFER_FORM | ENGRAVING_ANGLE',
  `VARIANT_CODE`  varchar(24) NOT NULL COMMENT 'Value on that axis, e.g. AlTiN, FX1, Ground, Double Cut, A30',
  `VARIANT_PART`  varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'The sibling part number',
  `IS_SELF`       tinyint(1)  NOT NULL DEFAULT 0 COMMENT 'VARIANT_PART = PART',
  `LEGACY_COLUMN` varchar(24) NOT NULL COMMENT 'Source column, for traceability back to master_product_data',
  -- VARIANT_PART is part of the key: a part can have two siblings on one axis
  -- (legacy `none` holds a comma-separated pair on 2 rows).
  PRIMARY KEY (`PART`, `VARIANT_AXIS`, `VARIANT_CODE`, `VARIANT_PART`),
  KEY `idx_variant_part`  (`VARIANT_PART`),
  KEY `idx_variant_axis`  (`VARIANT_AXIS`, `VARIANT_CODE`),
  CONSTRAINT `fk_variant_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- dimension_precision_exceptions
--
-- Records every row where 4-decimal storage rounded the true value, and preserves
-- the exact value. Sparse by design: a boolean column per dimension would be
-- ~9 near-empty columns and could not carry the exact figure.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dimension_precision_exceptions` (
  `PART`           varchar(13) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ATTRIBUTE`      varchar(64)    NOT NULL COMMENT 'Column name, e.g. DC',
  `STORED_VALUE`   decimal(9,4)   NOT NULL COMMENT 'What product_core holds, e.g. 2.3813',
  `EXACT_TEXT`     varchar(32)    NOT NULL COMMENT 'Exact human form, e.g. 3/32',
  `EXACT_DECIMAL`  decimal(20,10) DEFAULT NULL COMMENT 'Full-precision value, e.g. 2.3812500000',
  PRIMARY KEY (`PART`, `ATTRIBUTE`),
  KEY `idx_dpe_attribute` (`ATTRIBUTE`),
  CONSTRAINT `fk_dpe_part` FOREIGN KEY (`PART`) REFERENCES `product_core` (`PART`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- attribute_dictionary
--
-- The legend, made queryable. Replaces the two duplicated ~250-line label switch
-- statements in assets/js/tool-filter.js, and carries the legacy column mapping
-- that the consumer migration (phase 2) will use.
--
-- TOOL_TYPE and SERIES use '' as the wildcard rather than NULL so they can sit in
-- a unique key. Lookup precedence: exact SERIES, then exact TOOL_TYPE, then ''.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `attribute_dictionary` (
  `ID`                  int(11)      NOT NULL AUTO_INCREMENT,
  `COLUMN_NAME`         varchar(64)  NOT NULL,
  `HOST_TABLE`          varchar(32)  NOT NULL COMMENT 'product_core | milling_attributes | ...',
  `TOOL_TYPE`           varchar(16)  NOT NULL DEFAULT '' COMMENT '"" = applies to all tool types',
  `SERIES`              varchar(25)  NOT NULL DEFAULT '' COMMENT '"" = applies to all series',
  `DISPLAY_LABEL`       varchar(64)  NOT NULL COMMENT 'Seeded from inc/column-mapping.php',
  `ISO_CODE`            varchar(16)  DEFAULT NULL,
  `ISO_STANDARD`        varchar(24)  DEFAULT NULL COMMENT 'ISO 13399 | ISO 1832 | ISO 529 | ISO 3166-1 | ANSI B94.53',
  `CODE_VERIFIED`       enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y = confirmed against a published ISO 13399 parameter dictionary',
  `CANONICAL_UNIT`      varchar(8)   DEFAULT NULL COMMENT 'MM | DEG | COUNT | NULL for text',
  `UNIT_CODE`           varchar(4)   DEFAULT NULL COMMENT 'UN/CEFACT Rec 20: MMT | DD | C62',
  `DECIMALS`            tinyint(4)   DEFAULT NULL,
  `IS_DIMENSION`        tinyint(1)   NOT NULL DEFAULT 0,
  `SORT_ORDER`          smallint(6)  NOT NULL DEFAULT 0,
  `LEGACY_COLUMN_NAME`  varchar(128) DEFAULT NULL COMMENT 'Comma-separated legacy source column(s)',
  `WOO_ATTRIBUTE`       varchar(64)  DEFAULT NULL COMMENT 'From inc/attribute-mapping.php, for phase 2',
  `NOTES`               varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  -- HOST_TABLE is part of the key: a column name is only unique within its
  -- table (LPR appears in product_core and holemaking_attributes; S and W1 in
  -- both inserts_attributes and specialty_attributes).
  UNIQUE KEY `uq_dict` (`HOST_TABLE`, `COLUMN_NAME`, `TOOL_TYPE`, `SERIES`),
  KEY `idx_dict_legacy` (`LEGACY_COLUMN_NAME`),
  KEY `idx_dict_table`  (`HOST_TABLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
