import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const type = process.argv[2];
const name = process.argv[3];

if (!type || !name) {
	console.error('❌ Error: Missing arguments.');
	console.error('👉 Usage: npm run make:test <js|php> <FileNameOrPath>');
	console.error('Example: npm run make:test js sources/js/common/deviceMenu.js');
	process.exit(1);
}

const themeRoot = path.resolve(__dirname, '..');
const testsDir = path.join(themeRoot, 'tests');

// Resolve Source File Path
let sourcePath = path.isAbsolute(name) ? name : path.resolve(themeRoot, name);
if (!fs.existsSync(sourcePath)) {
	// Try common locations if not found
	const alternatives = [
		path.join(themeRoot, 'sources/js', name),
		path.join(themeRoot, 'sources/js/common', name),
		path.join(themeRoot, 'sources/js/modules', name),
		path.join(themeRoot, 'includes', name),
	];
	for (const alt of alternatives) {
		if (fs.existsSync(alt)) {
			sourcePath = alt;
			break;
		}
		if (fs.existsSync(alt + (type === 'js' ? '.js' : '.php'))) {
			sourcePath = alt + (type === 'js' ? '.js' : '.php');
			break;
		}
	}
}

const baseName = path.basename(sourcePath, path.extname(sourcePath));
const relativeSourcePath = path.relative(path.join(testsDir, type), sourcePath).replace(/\\/g, '/');

const getSourceContent = () => {
	return fs.existsSync(sourcePath) ? fs.readFileSync(sourcePath, 'utf8') : '';
};

if (type === 'js') {
	const jsTestsDir = path.join(testsDir, 'js');
	if (!fs.existsSync(jsTestsDir)) fs.mkdirSync(jsTestsDir, { recursive: true });

	const fileName = `${baseName}.test.js`;
	const filePath = path.join(jsTestsDir, fileName);

	const content = getSourceContent();
	const functions = [];

	// Regex for exports
	const exportRegex = /export\s+(?:const|function|let|var)\s+([a-zA-Z0-9_]+)/g;
	let match;
	while ((match = exportRegex.exec(content)) !== null) {
		functions.push(match[1]);
	}
	if (content.includes('export default')) {
		functions.push('defaultExport');
	}

	let testBlocks = functions.map(fn => `    it('should test ${fn} functionality', () => {\n        expect(true).toBe(true);\n    });`).join('\n\n');

	if (functions.length === 0) {
		testBlocks = `    it('should behave as expected', () => {\n        expect(true).toBe(true);\n    });`;
	}

	const template = `import { describe, expect, it } from '@jest/globals';
// import ${baseName} from '${relativeSourcePath}';

describe('${baseName}', () => {
${testBlocks}
});
`;

	if (fs.existsSync(filePath)) {
		console.error(`⚠️ Test file already exists: ${filePath}`);
	} else {
		fs.writeFileSync(filePath, template, 'utf8');
		console.log(`✅ Created JS test file: tests/js/${fileName}`);
		if (fs.existsSync(sourcePath)) {
			console.log(`📂 Automatically parsed functions from: ${path.relative(themeRoot, sourcePath)}`);
		}
	}

} else if (type === 'php') {
	const phpTestsDir = path.join(testsDir, 'php');
	if (!fs.existsSync(phpTestsDir)) fs.mkdirSync(phpTestsDir, { recursive: true });

	const classNameForCode = baseName.replace(/[^a-zA-Z0-9]/g, '');
	const fileName = `test-${baseName.toLowerCase()}.php`;
	const filePath = path.join(phpTestsDir, fileName);

	const content = getSourceContent();
	const methods = [];
	const methodRegex = /public\s+function\s+([a-zA-Z0-9_]+)/g;
	let match;
	while ((match = methodRegex.exec(content)) !== null) {
		methods.push(match[1]);
	}

	let testMethods = methods.map(m => `    public function test_${m}() {\n        $this->assertTrue(true);\n    }`).join('\n\n');

	if (methods.length === 0) {
		testMethods = `    public function test_sample_assertion() {\n        $this->assertTrue(true);\n    }`;
	}

	const template = `<?php
use PHPUnit\\Framework\\TestCase;

class ${classNameForCode}Test extends TestCase {
${testMethods}
}
`;

	if (fs.existsSync(filePath)) {
		console.error(`⚠️ Test file already exists: ${filePath}`);
	} else {
		fs.writeFileSync(filePath, template, 'utf8');
		console.log(`✅ Created PHP test file: tests/php/${fileName}`);
		if (fs.existsSync(sourcePath)) {
			console.log(`📂 Automatically parsed methods from: ${path.relative(themeRoot, sourcePath)}`);
		}
	}

} else {
	console.error('❌ Error: Invalid type. Use "js" or "php".');
	process.exit(1);
}

