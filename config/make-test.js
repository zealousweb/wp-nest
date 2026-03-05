import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const type = process.argv[2];
const name = process.argv[3];

if (!type || !name) {
	console.error('❌ Error: Missing arguments.');
	console.error('👉 Usage: npm run make:test <js|php> <FileName>');
	console.error('Example: npm run make:test js Fancybox');
	process.exit(1);
}

const themeRoot = path.resolve(__dirname, '..');
const testsDir = path.join(themeRoot, 'tests');

// Sanitize name (remove paths and extensions)
const baseName = path.basename(name, path.extname(name));

if (type === 'js') {
	const jsTestsDir = path.join(testsDir, 'js');
	if (!fs.existsSync(jsTestsDir)) fs.mkdirSync(jsTestsDir, { recursive: true });

	const fileName = `${baseName}.test.js`;
	const filePath = path.join(jsTestsDir, fileName);

	const template = `import { describe, expect, it } from '@jest/globals';

describe('${baseName}', () => {
    it('should behave as expected', () => {
        expect(true).toBe(true);
    });
});
`;

	if (fs.existsSync(filePath)) {
		console.error(`⚠️ Test file already exists: ${filePath}`);
	} else {
		fs.writeFileSync(filePath, template, 'utf8');
		console.log(`✅ Created JS test file: tests/js/${fileName}`);
	}

} else if (type === 'php') {
	const phpTestsDir = path.join(testsDir, 'php');
	if (!fs.existsSync(phpTestsDir)) fs.mkdirSync(phpTestsDir, { recursive: true });

	const className = baseName.replace(/[^a-zA-Z0-9]/g, '');
	const fileName = `test-${baseName.toLowerCase()}.php`;
	const filePath = path.join(phpTestsDir, fileName);

	const template = `<?php
use PHPUnit\\Framework\\TestCase;

class ${className}Test extends TestCase {
    public function test_sample_assertion() {
        $this->assertTrue(true);
    }
}
`;

	if (fs.existsSync(filePath)) {
		console.error(`⚠️ Test file already exists: ${filePath}`);
	} else {
		fs.writeFileSync(filePath, template, 'utf8');
		console.log(`✅ Created PHP test file: tests/php/${fileName}`);
	}

} else {
	console.error('❌ Error: Invalid type. Use "js" or "php".');
	process.exit(1);
}
