guard :livereload do
  watch(%r{.+\.(css|js|html?)$})
end

if File.exists?("./config.rb")
  # Compile on start.
  puts `compass compile --time --quiet`
  # https://github.com/guard/guard-compass
  guard :compass do
    watch(%r{(.*)\.s[ac]ss$})
  end
end

guard :jammit, :output_folder => 'public/js' do
  watch(%r{^assets/js/(.*)\.js$})
end
