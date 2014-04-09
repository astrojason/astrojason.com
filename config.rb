http_path = "/"
css_dir = "public/css"
sass_dir = "source/styles"
images_dir = "public/img"
javascripts_dir = "public/js"

# Add ".min" to the filename (https://coderwall.com/p/5by4ww)
on_stylesheet_saved do |file|
  if File.exists?(file)
    filename = File.basename(file, File.extname(file))
    File.rename(file, css_dir + "/" + filename + ".min" + File.extname(file))
  end
end

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false
