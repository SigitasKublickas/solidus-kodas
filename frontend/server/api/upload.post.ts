import { defineEventHandler, readMultipartFormData, H3Event, createError } from 'h3';
import { promises as fs } from 'fs';
import path from 'path';

export default defineEventHandler(async (event: H3Event) => {
  const formData = await readMultipartFormData(event);
  
  if (!formData) {
    throw createError({
      statusCode: 400,
      statusMessage: 'No file uploaded',
    });
  }

  const file = formData.find(item => item.type && item.filename);
  
  if (!file) {
    throw createError({
      statusCode: 400,
      statusMessage: 'File not found',
    });
  }

  if (file.type !== 'image/png') {
    throw createError({
      statusCode: 400,
      statusMessage: 'Only PNG files are allowed',
    });
  }

  const uploadDir = path.join(process.cwd(), 'public' ,'images');
  await fs.mkdir(uploadDir, { recursive: true });

  const fileName = `${file.filename}`;
  const filePath = path.join(uploadDir, fileName);

  await fs.writeFile(filePath, file.data);

  return { filename: fileName, filepath: `/images/${fileName}` };
});